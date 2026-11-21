<?php

namespace App\Http\Controllers\Admin;

use App\Services\Leads;
use App\Support\Csv;
use App\Support\Security\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Lead management: list with filters and export (/admin/leads) and one lead with its notes (/admin/lead?id=). */
class LeadsController extends AdminController
{
    private const PER_PAGE = 25;

    /** GET|POST /admin/leads */
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->indexAction($request);
        }

        $f = [
            'q' => trim((string) $request->query('q', '')),
            'status' => (string) $request->query('status', ''),
            'source' => (string) $request->query('source', ''),
            'assigned' => (string) $request->query('assigned', ''),
            'due' => (string) $request->query('due', ''),
        ];
        $today = date('Y-m-d');

        $query = DB::table('crm_leads as l');
        if ($f['q'] !== '') {
            $term = '%' . $f['q'] . '%';
            $query->where(fn ($q) => $q->where('l.name', 'like', $term)->orWhere('l.email', 'like', $term)
                ->orWhere('l.phone', 'like', $term)->orWhere('l.nationality', 'like', $term));
        }
        if (in_array($f['status'], Leads::STATUSES, true)) {
            $query->where('l.status', $f['status']);
        }
        if (array_key_exists($f['source'], Leads::SOURCES)) {
            $query->where('l.source', $f['source']);
        }
        if ($f['assigned'] === 'none') {
            $query->whereNull('l.assigned_to');
        } elseif (ctype_digit($f['assigned'])) {
            $query->where('l.assigned_to', (int) $f['assigned']);
        }
        if ($f['due'] === 'today') {
            $query->where('l.next_follow_up', $today);
        } elseif ($f['due'] === 'overdue') {
            $query->where('l.next_follow_up', '<', $today)->whereNotIn('l.status', ['Enrolled', 'Not interested']);
        }

        if ($request->query('export') === 'csv') {
            return $this->export(clone $query);
        }

        $total = (int) (clone $query)->count();
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($pages, max(1, (int) $request->query('page', 1)));
        $leads = $query->orderByRaw("CASE WHEN l.status = 'New' THEN 0 ELSE 1 END")->orderByDesc('l.updated_at')->orderByDesc('l.id')
            ->offset(($page - 1) * self::PER_PAGE)->limit(self::PER_PAGE)->get()->map(fn ($r) => (array) $r)->all();

        $counts = DB::table('crm_leads')->selectRaw('status, COUNT(*) AS n')->groupBy('status')->pluck('n', 'status')->all();

        return $this->page('admin.leads.index', 'Leads', 'leads', 'Enquiries and prospective students to follow up', [
            'leads' => $leads, 'f' => $f, 'total' => $total, 'page' => $page, 'pages' => $pages,
            'counts' => $counts, 'allCount' => array_sum($counts),
            'dueToday' => (int) DB::table('crm_leads')->where('next_follow_up', $today)->count(),
            'overdue' => (int) DB::table('crm_leads')->where('next_follow_up', '<', $today)->whereNotIn('status', ['Enrolled', 'Not interested'])->count(),
            'statuses' => Leads::STATUSES, 'sources' => Leads::SOURCES, 'admins' => $this->admins(),
            'flash' => session()->pull('lead_flash'), 'today' => $today,
        ]);
    }

    private function indexAction(Request $request)
    {
        $action = (string) $request->input('action');

        if ($action === 'create') {
            $name = trim((string) $request->input('name'));
            $email = strtolower(trim((string) $request->input('email')));
            if ($name === '' || ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL))) {
                session()->put('lead_flash', 'Not saved: a name is required and the email must be valid.');

                return redirect('/admin/leads');
            }
            $source = (string) $request->input('source', 'manual');
            $follow = (string) $request->input('next_follow_up');
            $id = DB::table('crm_leads')->insertGetId([
                'source' => array_key_exists($source, Leads::SOURCES) ? $source : 'manual',
                'name' => mb_substr($name, 0, 160), 'email' => $email !== '' ? mb_substr($email, 0, 190) : null,
                'phone' => $this->cut($request->input('phone'), 60), 'nationality' => $this->cut($request->input('nationality'), 100),
                'program' => $this->cut($request->input('program'), 160), 'message' => $this->cut($request->input('message'), 5000),
                'status' => 'New', 'next_follow_up' => $this->date($follow),
                'created_at' => now(), 'updated_at' => now(),
            ]);
            Leads::note($id, 'Lead added by staff.', $this->adminId($request), $this->adminName($request));
            session()->put('lead_flash', 'Lead added.');

            return redirect('/admin/lead?id=' . $id);
        }

        if ($action === 'import_registered') {
            $n = Leads::importRegistered();
            Audit::record('leads.import_registered', ['created' => $n]);
            session()->put('lead_flash', $n . ' registered student' . ($n === 1 ? '' : 's') . ' added as leads.');
        }

        return redirect('/admin/leads');
    }

    /** GET|POST /admin/lead?id= */
    public function show(Request $request)
    {
        $id = (int) $request->query('id');
        $lead = DB::table('crm_leads')->where('id', $id)->first();
        if (! $lead) {
            session()->put('lead_flash', 'That lead no longer exists.');

            return redirect('/admin/leads');
        }

        if ($request->isMethod('post')) {
            $action = (string) $request->input('action');
            $who = $this->adminName($request);

            if ($action === 'update') {
                $status = (string) $request->input('status');
                $assigned = (string) $request->input('assigned_to');
                $changes = [
                    'status' => in_array($status, Leads::STATUSES, true) ? $status : $lead->status,
                    'assigned_to' => ctype_digit($assigned) && isset($this->admins()[(int) $assigned]) ? (int) $assigned : null,
                    'next_follow_up' => $this->date((string) $request->input('next_follow_up')),
                    'phone' => $this->cut($request->input('phone'), 60), 'nationality' => $this->cut($request->input('nationality'), 100),
                    'program' => $this->cut($request->input('program'), 160),
                    'updated_at' => now(),
                ];
                DB::table('crm_leads')->where('id', $id)->update($changes);
                $log = [];
                if ($changes['status'] !== $lead->status) {
                    $log[] = 'Status: ' . $lead->status . ' → ' . $changes['status'];
                }
                if ((string) $changes['next_follow_up'] !== (string) $lead->next_follow_up) {
                    $log[] = 'Next follow-up: ' . ($changes['next_follow_up'] ?: 'none');
                }
                if ((int) $changes['assigned_to'] !== (int) $lead->assigned_to) {
                    $log[] = 'Assigned to: ' . ($changes['assigned_to'] ? $this->admins()[$changes['assigned_to']] : 'nobody');
                }
                if ($log) {
                    Leads::note($id, implode('. ', $log) . '.', $this->adminId($request), $who);
                }
                session()->put('lead_flash', 'Lead updated.');
            } elseif ($action === 'note') {
                $note = trim((string) $request->input('note'));
                if ($note !== '') {
                    Leads::note($id, mb_substr($note, 0, 5000), $this->adminId($request), $who);
                    DB::table('crm_leads')->where('id', $id)->update(['updated_at' => now()]);
                    session()->put('lead_flash', 'Note added.');
                }
            } elseif ($action === 'delete') {
                DB::table('crm_lead_notes')->where('lead_id', $id)->delete();
                DB::table('crm_leads')->where('id', $id)->delete();
                session()->put('lead_flash', 'Lead deleted.');

                return redirect('/admin/leads');
            }

            return redirect('/admin/lead?id=' . $id);
        }

        $application = $lead->user_id
            ? DB::table('applications')->where('user_id', $lead->user_id)->first(['is_submitted', 'status', 'submitted_at'])
            : null;

        return $this->page('admin.leads.show', $lead->name, 'leads', (Leads::SOURCES[$lead->source] ?? $lead->source) . ' · added ' . substr((string) $lead->created_at, 0, 10), [
            'lead' => (array) $lead,
            'notes' => DB::table('crm_lead_notes')->where('lead_id', $id)->orderByDesc('id')->get()->map(fn ($r) => (array) $r)->all(),
            'statuses' => Leads::STATUSES, 'sources' => Leads::SOURCES, 'admins' => $this->admins(),
            'application' => $application ? (array) $application : null,
            'flash' => session()->pull('lead_flash'),
        ]);
    }

    private function export($query)
    {
        $admins = $this->admins();
        $rows = $query->orderByDesc('l.id')->get();
        Audit::record('leads.export', ['rows' => count($rows)]);

        $head = ['ID', 'Name', 'Email', 'Phone', 'Nationality', 'Program', 'Source', 'Status', 'Assigned to', 'Next follow-up', 'Message', 'Created', 'Updated'];
        $out = chr(0xEF) . chr(0xBB) . chr(0xBF) . implode(',', $head) . chr(13) . chr(10);
        foreach ($rows as $r) {
            $out .= implode(',', array_map([Csv::class, 'cell'], [$r->id, $r->name, $r->email, $r->phone, $r->nationality, $r->program,
                Leads::SOURCES[$r->source] ?? $r->source, $r->status, $admins[$r->assigned_to] ?? '', $r->next_follow_up,
                $r->message, $r->created_at, $r->updated_at])) . chr(13) . chr(10);
        }

        return response($out, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="leads-' . date('Y-m-d') . '.csv"',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    /** @return array<int, string> admin id => name */
    private function admins(): array
    {
        static $admins = null;

        return $admins ??= DB::table('users')->where('role', 'admin')->orderBy('full_name')->pluck('full_name', 'id')->all();
    }

    private function adminId(Request $request): ?int
    {
        $id = $request->session()->get('user_id');

        return $id ? (int) $id : null;
    }

    private function adminName(Request $request): string
    {
        return (string) ($request->session()->get('full_name') ?: 'Admin');
    }

    private function cut($value, int $max): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : mb_substr($value, 0, $max);
    }

    private function date(string $value): ?string
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value) !== false ? $value : null;
    }
}
