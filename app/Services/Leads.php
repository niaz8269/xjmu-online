<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Lead management: fixed lists, capture from the public forms, and the import of registered-but-not-applied students. */
class Leads
{
    public const STATUSES = ['New', 'Contacted', 'Interested', 'Applied', 'Enrolled', 'Not interested'];

    public const SOURCES = [
        'contact' => 'Contact form', 'partner' => 'Partner form', 'event' => 'Event / webinar', 'popup' => 'Home page pop-up',
        'registered' => 'Registered, not applied', 'manual' => 'Added by staff',
        'whatsapp' => 'WhatsApp', 'phone' => 'Phone call', 'agent' => 'Agent / consultant',
    ];

    /** Where every public enquiry form is emailed: the admin-set address (Leads page), else config/forms.php. */
    public static function notifyTo(): string
    {
        $v = trim((string) \App\Support\Settings::get('form_notify_to', ''));

        return filter_var($v, FILTER_VALIDATE_EMAIL) ? $v : (string) config('forms.notify_to');
    }

    /** Save the address enquiry emails go to. Returns false when it is not a valid email. */
    public static function setNotifyTo(string $email): bool
    {
        $email = trim($email);
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        DB::table('site_settings')->updateOrInsert(['setting_key' => 'form_notify_to'], [
            'setting_value' => $email, 'group_name' => 'contact', 'label' => 'Enquiry forms are emailed to', 'input_type' => 'email', 'sort_order' => 5, 'updated_at' => now(),
        ]);
        \App\Support\Settings::flush();

        return true;
    }

    /**
     * Save an enquiry from a public form. Never throws: a database problem must not break the form or its email.
     * A repeat enquiry from the same email is added to the existing lead as a note instead of a duplicate lead.
     */
    public static function capture(string $source, array $data): ?int
    {
        try {
            $email = strtolower(trim((string) ($data['email'] ?? '')));
            $name = trim((string) ($data['name'] ?? ''));
            if ($name === '' && $email === '') {
                return null;
            }
            $message = trim((string) ($data['message'] ?? ''));
            $now = now();

            $existing = $email !== '' ? DB::table('crm_leads')->whereRaw('LOWER(email) = ?', [$email])->orderBy('id')->first() : null;
            if ($existing) {
                $label = self::SOURCES[$source] ?? $source;
                $note = 'New enquiry (' . $label . ')' . (! empty($data['subject']) ? ': ' . $data['subject'] : '') . ($message !== '' ? "\n" . $message : '');
                self::note((int) $existing->id, $note, null, 'Website');
                DB::table('crm_leads')->where('id', $existing->id)->update(['updated_at' => $now]);

                return (int) $existing->id;
            }

            return (int) DB::table('crm_leads')->insertGetId([
                'source' => array_key_exists($source, self::SOURCES) ? $source : 'manual',
                'name' => mb_substr($name !== '' ? $name : $email, 0, 160),
                'email' => $email !== '' ? mb_substr($email, 0, 190) : null,
                'phone' => self::cut($data['phone'] ?? null, 60),
                'nationality' => self::cut($data['nationality'] ?? null, 100),
                'program' => self::cut($data['program'] ?? null, 160),
                'subject' => self::cut($data['subject'] ?? null, 200),
                'message' => $message !== '' ? $message : null,
                'status' => 'New',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Lead capture failed: ' . $e->getMessage());

            return null;
        }
    }

    public static function note(int $leadId, string $note, ?int $adminId, ?string $adminName): void
    {
        DB::table('crm_lead_notes')->insert([
            'lead_id' => $leadId, 'admin_id' => $adminId, 'admin_name' => $adminName,
            'note' => $note, 'created_at' => now(),
        ]);
    }

    /**
     * Create a lead for every registered student who has not submitted an application and is not a lead yet.
     * Read-only on users/applications. Returns the number of leads created.
     */
    public static function importRegistered(): int
    {
        $rows = DB::table('users as u')
            ->leftJoin('applications as a', 'a.user_id', '=', 'u.id')
            ->leftJoin('crm_leads as l', 'l.user_id', '=', 'u.id')
            ->whereNull('l.id')
            ->where(fn ($q) => $q->whereNull('u.role')->orWhere('u.role', '!=', 'admin'))
            ->where(fn ($q) => $q->whereNull('a.user_id')->orWhere(fn ($q2) => $q2->whereRaw('COALESCE(a.is_submitted, 0) != 1')))
            ->get(['u.id', 'u.full_name', 'u.email', 'u.created_at', 'a.degree_applying_for', 'a.major_applying_for']);

        $now = now();
        $created = 0;
        foreach ($rows as $r) {
            $program = trim(($r->degree_applying_for ?? '') . ' ' . ($r->major_applying_for ?? ''));
            DB::table('crm_leads')->insert([
                'source' => 'registered', 'name' => mb_substr((string) ($r->full_name ?: $r->email), 0, 160),
                'email' => $r->email ? strtolower($r->email) : null, 'program' => $program !== '' ? mb_substr($program, 0, 160) : null,
                'status' => 'New', 'user_id' => $r->id,
                'message' => 'Registered on the portal' . ($r->created_at ? ' on ' . substr((string) $r->created_at, 0, 10) : '') . ' and has not submitted an application.',
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $created++;
        }

        return $created;
    }

    private static function cut($value, int $max): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : mb_substr($value, 0, $max);
    }
}
