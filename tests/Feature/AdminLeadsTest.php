<?php

namespace Tests\Feature;

use App\Services\Leads;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Lead management: capture from forms, admin list/detail, follow-ups, import of registered students, export. */
class AdminLeadsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'boss' . uniqid() . '@example.com', 'password' => 'x', 'role' => 'admin', 'is_verified' => 1,
        ]);

        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss', 'admin_last_activity' => time(), '_token' => 'tok'];
    }

    public function test_capture_creates_one_lead_per_email_and_adds_repeat_enquiries_as_notes(): void
    {
        $id = Leads::capture('contact', ['name' => 'Ann Lee', 'email' => 'Ann@Example.test', 'phone' => '+92 300', 'nationality' => 'Pakistan', 'subject' => 'Fees', 'message' => 'How much?']);
        $again = Leads::capture('event', ['name' => 'Ann', 'email' => 'ann@example.test', 'message' => 'Webinar please']);

        $this->assertSame($id, $again);
        $this->assertSame(1, DB::table('crm_leads')->count());
        $this->assertSame('ann@example.test', DB::table('crm_leads')->value('email'));
        $this->assertSame('New', DB::table('crm_leads')->value('status'));
        $this->assertStringContainsString('Webinar please', DB::table('crm_lead_notes')->where('lead_id', $id)->value('note'));
        $this->assertNull(Leads::capture('contact', ['name' => '', 'email' => '']));
    }

    public function test_leads_pages_are_admin_only(): void
    {
        $this->get('/admin/leads')->assertRedirect();
        $this->get('/admin/lead?id=1')->assertRedirect();
        $this->get('/admin/leads?export=csv')->assertRedirect();
    }

    public function test_admin_adds_updates_notes_filters_exports_and_deletes_a_lead(): void
    {
        $admin = $this->admin();
        $this->withSession($admin)->get('/admin/leads')->assertOk()->assertSee('No leads here yet');

        $this->withSession($admin)->post('/admin/leads', ['_token' => 'tok', 'action' => 'create', 'name' => '=Bob', 'email' => 'bob@example.test',
            'phone' => '0300 1234567', 'source' => 'whatsapp', 'next_follow_up' => '2020-01-01'])->assertRedirect();
        $id = (int) DB::table('crm_leads')->value('id');
        $this->assertSame('whatsapp', DB::table('crm_leads')->value('source'));

        // invalid input is refused
        $this->withSession($admin)->post('/admin/leads', ['_token' => 'tok', 'action' => 'create', 'name' => '', 'email' => 'bad'])->assertRedirect();
        $this->assertSame(1, DB::table('crm_leads')->count());

        $this->withSession($admin)->get('/admin/leads?due=overdue')->assertOk()->assertSee('bob@example.test')->assertSee('overdue');
        $this->withSession($admin)->get('/admin/leads?status=Enrolled')->assertOk()->assertDontSee('bob@example.test');

        $this->withSession($admin)->post('/admin/lead?id=' . $id, ['_token' => 'tok', 'action' => 'update', 'status' => 'Contacted',
            'assigned_to' => (string) $admin['user_id'], 'next_follow_up' => '2030-05-01', 'phone' => '0300 1234567'])->assertRedirect();
        $lead = DB::table('crm_leads')->first();
        $this->assertSame('Contacted', $lead->status);
        $this->assertSame((int) $admin['user_id'], (int) $lead->assigned_to);
        $this->assertSame('2030-05-01', substr((string) $lead->next_follow_up, 0, 10));

        // a status outside the list is ignored
        $this->withSession($admin)->post('/admin/lead?id=' . $id, ['_token' => 'tok', 'action' => 'update', 'status' => 'Hacked'])->assertRedirect();
        $this->assertSame('Contacted', DB::table('crm_leads')->value('status'));

        $this->withSession($admin)->post('/admin/lead?id=' . $id, ['_token' => 'tok', 'action' => 'note', 'note' => 'Called, will apply <b>soon</b>'])->assertRedirect();
        $this->withSession($admin)->get('/admin/lead?id=' . $id)->assertOk()
            ->assertSee('Called, will apply &lt;b&gt;soon&lt;/b&gt;', false)->assertSee('Status: New → Contacted');

        $csv = $this->withSession($admin)->get('/admin/leads?export=csv')->assertOk()->assertHeader('Content-Type', 'text/csv; charset=UTF-8')->getContent();
        $this->assertStringContainsString('"\'=Bob","bob@example.test"', $csv);

        $this->withSession($admin)->post('/admin/lead?id=' . $id, ['_token' => 'tok', 'action' => 'delete'])->assertRedirect('/admin/leads');
        $this->assertSame(0, DB::table('crm_leads')->count());
        $this->assertSame(0, DB::table('crm_lead_notes')->count());
    }

    public function test_import_adds_registered_students_who_have_not_submitted_once(): void
    {
        $admin = $this->admin();
        $mk = fn (string $n) => DB::table('users')->insertGetId(['full_name' => $n, 'email' => strtolower($n) . '@example.test', 'password' => 'x', 'role' => 'user', 'created_at' => now()]);
        $noApp = $mk('NoApp');
        $draft = $mk('Draft');
        $done = $mk('Done');
        DB::table('applications')->insert(['user_id' => $draft, 'is_submitted' => 0, 'status' => 'draft', 'major_applying_for' => 'MBBS']);
        DB::table('applications')->insert(['user_id' => $done, 'is_submitted' => 1, 'status' => 'Pending']);

        $this->withSession($admin)->post('/admin/leads', ['_token' => 'tok', 'action' => 'import_registered'])->assertRedirect('/admin/leads');
        $this->assertEqualsCanonicalizing([$noApp, $draft], DB::table('crm_leads')->pluck('user_id')->map(fn ($v) => (int) $v)->all());

        $this->withSession($admin)->post('/admin/leads', ['_token' => 'tok', 'action' => 'import_registered'])->assertRedirect();
        $this->assertSame(2, DB::table('crm_leads')->count());   // no duplicates, admins and submitted students skipped
        $this->assertSame(3 + 1, DB::table('users')->count()); // users untouched
    }

    public function test_contact_form_saves_a_lead_even_if_the_email_cannot_be_sent(): void
    {
        $this->post('/send_contact_email', ['name' => 'Cy', 'email' => 'cy@example.test', 'nationality' => 'Nepal', 'subject' => 'MBBS', 'message' => 'Hello']);
        $this->assertSame('cy@example.test', DB::table('crm_leads')->value('email'));
        $this->assertSame('contact', DB::table('crm_leads')->value('source'));
    }
}
