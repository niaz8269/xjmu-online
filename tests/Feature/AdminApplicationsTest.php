<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminApplicationsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(bool $super = false): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'boss' . uniqid() . '@example.com', 'password' => 'x', 'role' => 'admin',
            'is_superadmin' => $super ? 1 : 0, 'is_verified' => 1,
        ]);
        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss'];
    }

    private function applicant(string $email = 'stu@example.com'): int
    {
        $uid = DB::table('users')->insertGetId([
            'full_name' => 'Stu Dent', 'email' => $email, 'password' => 'x', 'role' => 'user', 'is_verified' => 1,
        ]);
        DB::table('applications')->insert(['user_id' => $uid, 'is_submitted' => 1, 'status' => 'Pending',
            'degree_applying_for' => 'MBBS', 'major_applying_for' => 'Medicine']);
        DB::table('personal_info')->insert(['user_id' => $uid, 'given_name' => 'Stu', 'surname' => 'Dent', 'tel' => '123']);
        DB::table('education_records')->insert(['user_id' => $uid, 'entry_type' => 'Education', 'education_level' => 'High School',
            'field_of_study' => 'Science', 'institute_name' => 'ABC', 'from_date' => '2015-01-01', 'to_date' => '2019-01-01']);
        return $uid;
    }

    public function test_guests_and_students_are_sent_to_login(): void
    {
        foreach (['applications', 'manage-applications', 'review_application?id=1', 'delete_application', 'export_pdf?user_id=1',
                  'download_application?user_id=1', 'update_personal'] as $p) {
            $this->get('/admin/' . $p)->assertRedirect('/login');
        }
        $this->withSession(['user_id' => 9, 'role' => 'user'])->get('/admin/applications')->assertRedirect('/login');
    }

    public function test_applications_list_filters_and_renders_a_csrf_delete_form(): void
    {
        $this->applicant();
        $r = $this->withSession($this->admin())->get('/admin/applications?search=stu&filter=submitted');
        // One table row per applicant; the delete form now lives on the application page.
        $r->assertOk()->assertSee('Stu Dent', false)->assertSee('stu@example.com', false)
            ->assertSee('review_application?id=', false)->assertSee('Showing 1 of 1', false)
            ->assertDontSee('action="delete_application"', false);
        $this->withSession($this->admin())->get('/admin/applications.php?filter=unsubmitted&search=zzz')->assertOk()->assertSee('No unsubmitted applications', false);
    }

    public function test_manage_applications_updates_remark_only_on_an_existing_application(): void
    {
        $uid = $this->applicant();
        $s = $this->admin();
        $this->withSession($s)->post('/admin/manage-applications', ['user_id' => $uid, 'status' => 'Call me'])
            ->assertOk()->assertSee('Remark updated successfully.');
        $this->assertSame('Call me', DB::table('applications')->where('user_id', $uid)->value('admin_remark'));

        $this->withSession($s)->post('/admin/manage-applications', ['user_id' => 999999, 'status' => 'x'])
            ->assertSee('No application exists for this user, so nothing was updated.');
        $this->assertSame(1, DB::table('applications')->count());
    }

    public function test_review_application_page_and_status_update(): void
    {
        $uid = $this->applicant();
        $s = $this->admin();
        $this->withSession($s)->get('/admin/review_application?id=' . $uid)->assertOk()->assertSee('Applicant: Stu Dent', false);
        $this->withSession($s)->get('/admin/review_application')->assertOk()->assertSee('Invalid user ID.');
        $this->withSession($s)->get('/admin/review_application?id=424242')->assertSee('Application not found.');

        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['status' => 'Rejected', 'admin_remark' => '  missing docs '])
            ->assertRedirect('/admin/applications')->assertSessionHas('flash', 'Application has been updated successfully.');
        $row = DB::table('applications')->where('user_id', $uid)->first();
        $this->assertSame('Rejected', $row->status);
        $this->assertSame('missing docs', $row->admin_remark);

        // unknown status falls back to Pending
        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['status' => 'Bogus', 'admin_remark' => ''])->assertRedirect('/admin/applications');
        $this->assertSame('Pending', DB::table('applications')->where('user_id', $uid)->value('status'));
    }

    public function test_delete_application_removes_rows_and_files_but_only_on_post(): void
    {
        $uid = $this->applicant();
        $keep = $this->applicant('other@example.com');
        $dir = public_path('uploads/admissions_test');
        @mkdir($dir, 0777, true);
        file_put_contents($dir . '/doc.txt', 'x');
        DB::table('documents')->insert(['user_id' => $uid, 'document_id' => 1, 'file_name' => 'doc.txt', 'file_path' => 'uploads/admissions_test/doc.txt']);
        $s = $this->admin();

        $this->withSession($s)->get('/admin/delete_application?user_id=' . $uid)->assertRedirect('/admin/applications');
        $this->assertSame(1, DB::table('applications')->where('user_id', $uid)->count());

        $this->withSession($s)->post('/admin/delete_application', ['user_id' => $uid])
            ->assertRedirect('/admin/applications')->assertSessionHas('flash', 'Application deleted successfully.');
        foreach (['applications', 'personal_info', 'education_records', 'documents'] as $t) {
            $this->assertSame(0, DB::table($t)->where('user_id', $uid)->count(), $t);
            $this->assertSame($t === 'documents' ? 0 : 1, DB::table($t)->where('user_id', $keep)->count(), "$t of other user");
        }
        $this->assertFileDoesNotExist($dir . '/doc.txt');
        @rmdir($dir);

        // the user account itself stays (only the application is deleted)
        $this->assertSame(1, DB::table('users')->where('id', $uid)->count());
    }

    public function test_update_personal(): void
    {
        $uid = $this->applicant();
        $s = $this->admin();
        $this->withSession($s)->get('/admin/update_personal')->assertRedirect('/admin/applications');
        $this->withSession($s)->post('/admin/update_personal', ['user_id' => 0])->assertRedirect('/admin/applications');
        $this->withSession($s)->post('/admin/update_personal', ['user_id' => $uid, 'given_name' => 'New', 'surname' => 'Name', 'dob' => '2000-02-03'])
            ->assertRedirect('/admin/applications')->assertSessionHas('flash', 'Personal information updated.');
        $p = DB::table('personal_info')->where('user_id', $uid)->first();
        $this->assertSame(['New', 'Name', '2000-02-03'], [$p->given_name, $p->surname, $p->dob]);
    }

    public function test_export_pdf(): void
    {
        $uid = $this->applicant();
        $s = $this->admin();
        $r = $this->withSession($s)->get('/admin/export_pdf?user_id=' . $uid);
        $r->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('Application_User_' . $uid . '.pdf', $r->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-', $r->getContent());
        $this->withSession($s)->get('/admin/export_pdf')->assertSee('No user selected.');
        $this->withSession($s)->get('/admin/export_pdf?user_id=777777')->assertSee('User not found.');
    }

    public function test_download_application_zip_contains_summary_and_documents(): void
    {
        $uid = $this->applicant();
        $dir = public_path('uploads/admissions_test');
        @mkdir($dir, 0777, true);
        file_put_contents($dir . '/passport.txt', 'passport');
        DB::table('documents')->insert(['user_id' => $uid, 'document_id' => 1, 'file_name' => 'p', 'file_path' => 'uploads/admissions_test/passport.txt']);

        $s = $this->admin();
        $r = $this->withSession($s)->get('/admin/download_application?user_id=' . $uid);
        $r->assertOk()->assertHeader('Content-Type', 'application/zip');
        $this->assertSame('attachment; filename=Application_Package_Stu_Dent.zip', $r->headers->get('Content-Disposition'));
        $bytes = $r->streamedContent();
        $this->assertStringStartsWith('PK', $bytes);
        $tmp = tempnam(sys_get_temp_dir(), 'z');
        file_put_contents($tmp, $bytes);
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($tmp));
        $names = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $names[] = $zip->getNameIndex($i);
        }
        $zip->close();
        unlink($tmp);
        sort($names);
        $this->assertSame(['Application_Summary.pdf', 'passport.txt'], $names);
        // applicant files never linger in the working folder
        $this->assertDirectoryDoesNotExist(storage_path('legacy/temp_zip/Stu_Dent'));

        unlink($dir . '/passport.txt');
        @rmdir($dir);
        $this->withSession($s)->get('/admin/download_application')->assertSee('User ID not provided.');
    }

    public function test_application_page_has_notes_history_documents_and_the_delete_form(): void
    {
        $uid = $this->applicant();
        $s = $this->admin() + ['_token' => 'tok'];

        $this->withSession($s)->get('/admin/review_application?id=' . $uid)->assertOk()
            ->assertSee('action="delete_application"', false)->assertSee('Internal notes and history')
            ->assertSee('No notes or changes recorded yet.')->assertSee('Emails to applicants are switched off.');

        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['_token' => 'tok', 'action' => 'note', 'note' => 'Called the <b>father</b>'])
            ->assertRedirect('/admin/review_application?id=' . $uid);
        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['_token' => 'tok', 'status' => 'Approved', 'admin_remark' => 'Welcome'])
            ->assertRedirect('/admin/applications');
        // Saving the same values again adds no history entry.
        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['_token' => 'tok', 'status' => 'Approved', 'admin_remark' => 'Welcome']);

        $log = \Illuminate\Support\Facades\DB::table('crm_application_log')->where('user_id', $uid)->orderBy('id')->get();
        $this->assertCount(2, $log);
        $this->assertSame(['note', 'status'], $log->pluck('type')->all());
        $this->assertSame('Approved', $log[1]->new_status);

        $this->withSession($s)->get('/admin/review_application?id=' . $uid)->assertOk()
            ->assertSee('Called the &lt;b&gt;father&lt;/b&gt;', false)->assertSee('Remark: Welcome');
    }

    public function test_applicant_emails_are_off_by_default_and_sent_when_switched_on(): void
    {
        $uid = $this->applicant();
        $s = $this->admin() + ['_token' => 'tok'];
        $mailer = app(\App\Services\SiteMailer::class);
        $this->app->instance(\App\Services\SiteMailer::class, $mailer);

        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['_token' => 'tok', 'status' => 'Rejected', 'admin_remark' => 'Missing passport']);
        $this->assertSame([], array_filter($mailer->outbox, fn ($m) => str_contains($m['subject'] ?? '', 'Update on your application')));

        config(['portal.notify_students' => true]);
        $this->withSession($s)->post('/admin/review_application?id=' . $uid, ['_token' => 'tok', 'status' => 'Approved', 'admin_remark' => 'Well done <script>']);
        $sent = array_values(array_filter($mailer->outbox, fn ($m) => str_contains($m['subject'] ?? '', 'Update on your application')));
        $this->assertCount(1, $sent);
        $this->assertSame('stu@example.com', $sent[0]['to']);
        $this->assertStringContainsString('has been <strong>approved</strong>', $sent[0]['html']);
        $this->assertStringContainsString('Well done &lt;script&gt;', $sent[0]['html']);
        $this->assertSame(1, \Illuminate\Support\Facades\DB::table('crm_application_log')->where('user_id', $uid)->where('type', 'email')->count());
    }
}
