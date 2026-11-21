<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;
use Illuminate\Http\UploadedFile;

class PortalSubmitTest extends PortalCase
{
    private function submit()
    {
        return $this->login()->postJson2('/submit_application.php');
    }

    private function error(string $message): array
    {
        return ['status' => 'error', 'message' => $message];
    }

    public function test_submit_checks_each_requirement_in_the_original_order(): void
    {
        $this->submit()->assertExactJson($this->error('Please complete Degree and Major in the application program section.'));

        Application::create(['user_id' => $this->user->id, 'degree_applying_for' => 'Other', 'major_applying_for' => 'Nursing', 'status' => 'draft']);
        $this->submit()->assertExactJson($this->error('Please complete Personal Details section before submission.'));

        PersonalInfo::query()->insert(['user_id' => $this->user->id, 'given_name' => 'Ann', 'surname' => '  ']);
        $this->submit()->assertExactJson($this->error('Please complete required personal fields: Surname, Date of Birth, Gender, Marital Status, Country of Birth, City of Birth, Religion, Nationality, Native Language, Passport No., Present Address, Permanent Address, Tel, Email, Emergency Contact Name, Emergency Gender, Emergency Relationship, Emergency Tel, Emergency Email, Emergency Address'));

        PersonalInfo::query()->where('user_id', $this->user->id)->update($this->personalData());
        $this->submit()->assertExactJson($this->error('Please complete all mandatory fields in Highest/Current Education section.'));

        $edu = ['user_id' => $this->user->id, 'education_level' => 'BSc', 'field_of_study' => 'x', 'country' => 'PK', 'institute_name' => 'I', 'qualification' => 'Q', 'from_date' => '2010-01-01', 'to_date' => '2012-01-01'];
        EducationRecord::query()->insert($edu + ['entry_type' => 'Highest']);
        $this->submit()->assertExactJson($this->error('Please complete all mandatory fields in Other Education section.'));

        EducationRecord::query()->insert(['entry_type' => 'Other', 'qualification' => ' '] + $edu);
        $this->submit()->assertExactJson($this->error('Please complete all mandatory fields in Other Education section.'));
        EducationRecord::query()->where('entry_type', 'Other')->update(['qualification' => 'Q']);

        $this->submit()->assertExactJson($this->error('Please upload all mandatory documents: ID Photo, Certificates of Highest Education, Transcripts of Highest Education, Study Plan, Passport Home Page, Physical Examination Record for Foreigner, Non-Criminal Record Report'));

        foreach ([1, 2, 3, 4, 7, 8] as $id) {
            Document::query()->insert(['user_id' => $this->user->id, 'document_id' => $id, 'file_name' => 'f', 'file_path' => 'p']);
        }
        $this->submit()->assertExactJson($this->error('Please upload all mandatory documents: Non-Criminal Record Report'));
        $this->assertEquals(0, Application::query()->first()->is_submitted);
    }

    public function test_successful_submission_marks_the_application_pending_and_cannot_be_repeated(): void
    {
        $this->completeApplication();

        $this->submit()->assertExactJson(['status' => 'success', 'message' => 'Application submitted successfully']);
        $app = Application::query()->where('user_id', $this->user->id)->first();
        $this->assertEquals(1, $app->is_submitted);
        $this->assertSame('Pending', $app->status);
        $this->assertNotNull($app->submitted_at);

        $this->submit()->assertExactJson($this->error('Application already submitted'));
        $this->login()->getJson('/get_submission_status')->assertExactJson(['is_submitted' => true]);
        $this->login()->getJson('/get_application_status')->assertJson(['is_submitted' => true, 'status' => 'Pending']);
    }

    public function test_submit_via_get_is_an_invalid_method(): void
    {
        $this->login()->getJson('/submit_application')->assertExactJson($this->error('Invalid request method'));
        $this->getJson('/submit_application.php')->assertExactJson($this->error('Invalid request method'));
    }

    public function test_guests_are_told_to_log_in(): void
    {
        $this->withSession(['_token' => self::TOKEN])->call('POST', '/submit_application', ['_token' => self::TOKEN])
            ->assertExactJson($this->error('User not logged in'));
    }

    // ---- save_documents -----------------------------------------------------------------------------------------

    private function pdf(string $name = 'doc.pdf', int $kb = 1): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, "%PDF-1.4\n" . str_repeat('a', max(0, $kb * 1024 - 9)));
    }

    private function image(string $name): UploadedFile
    {
        // 1x1 transparent PNG (the GD extension is not required to run the tests)
        return UploadedFile::fake()->createWithContent($name, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='));
    }

    private function upload(array $files)
    {
        return $this->login()->postJson2('/save_documents.php', [], $files);
    }

    public function test_upload_requires_at_least_one_file(): void
    {
        $this->upload([])->assertExactJson(['status' => 'error', 'errors' => ['Please select at least one file before clicking Verify and Save.']]);
        $this->upload(['other' => $this->pdf()])->assertJson(['status' => 'error', 'errors' => ['Please select at least one file before clicking Verify and Save.']]);
    }

    public function test_upload_stores_the_file_under_public_uploads_and_records_it(): void
    {
        $this->upload(['document_1' => $this->pdf('Passport scan.PDF')])
            ->assertExactJson(['status' => 'success', 'message' => '1 file(s) uploaded successfully.']);

        $doc = Document::query()->where('user_id', $this->user->id)->first();
        $this->assertSame(1, $doc->document_id);
        $this->assertSame('Passport scan.PDF', $doc->file_name);
        $this->assertMatchesRegularExpression('#^uploads/admissions/user_' . $this->user->id . '_doc_1_\d+_[0-9a-f]{13}\.pdf$#', $doc->file_path);
        $this->assertFileExists(public_path($doc->file_path));
        $this->assertNotNull($doc->uploaded_on);
    }

    public function test_upload_accepts_several_files_and_array_inputs_and_keeps_every_upload(): void
    {
        $this->upload(['document_5' => [$this->pdf('a.pdf'), $this->pdf('b.pdf', 2)], 'document_2' => $this->image('p.png')])
            ->assertExactJson(['status' => 'success', 'message' => '3 file(s) uploaded successfully.']);
        $this->upload(['document_2' => $this->pdf('q.pdf', 3)])->assertJson(['status' => 'success']);
        $this->assertSame(2, Document::query()->where('document_id', 2)->count());   // old files are not replaced
    }

    public function test_videos_go_to_the_videos_folder_with_a_10mb_limit(): void
    {
        $this->upload(['document_11' => UploadedFile::fake()->createWithContent('intro.mp4', "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom" . str_repeat('v', 2 * 1024 * 1024))])
            ->assertJson(['status' => 'success']);
        $this->assertStringStartsWith('uploads/videos/user_' . $this->user->id . '_doc_11_', Document::query()->value('file_path'));

        $this->upload(['document_11' => $this->pdf('big.pdf', 10 * 1024 + 1)])
            ->assertExactJson(['status' => 'error', 'errors' => ['Document 11 exceeds 10MB limit.']]);
    }

    public function test_upload_rules_size_extension_and_content_type(): void
    {
        $this->upload(['document_3' => $this->pdf('big.pdf', 5121)])
            ->assertExactJson(['status' => 'error', 'errors' => ['Document 3 exceeds 5MB limit.']]);
        $this->assertSame(['Document 3 exceeds 5MB limit.'], session('upload_errors'));

        $this->upload(['document_3' => UploadedFile::fake()->createWithContent('run.exe', 'MZ')])
            ->assertExactJson(['status' => 'error', 'errors' => ['Invalid file type for document 3.']]);
        $this->upload(['document_3' => UploadedFile::fake()->createWithContent('noext', 'x')])
            ->assertExactJson(['status' => 'error', 'errors' => ['Invalid file type for document 3.']]);

        // jpg that is really text
        $this->upload(['document_3' => UploadedFile::fake()->createWithContent('a.jpg', 'just text')])
            ->assertExactJson(['status' => 'error', 'errors' => ['Invalid file content type for document 3 (text/plain).']]);

        // every PDF is accepted whatever the sniffed type
        $this->upload(['document_3' => UploadedFile::fake()->createWithContent('odd.pdf', 'not really a pdf')])->assertJson(['status' => 'success']);
        $this->assertSame(1, Document::query()->count());
    }

    public function test_upload_collects_all_errors_but_still_saves_the_valid_files(): void
    {
        $this->upload(['document_1' => $this->pdf(), 'document_2' => UploadedFile::fake()->createWithContent('x.exe', 'MZ')])
            ->assertExactJson(['status' => 'error', 'errors' => ['Invalid file type for document 2.']]);
        $this->assertSame(1, Document::query()->count());
    }

    // ---- download_application -----------------------------------------------------------------------------------

    public function test_download_requires_a_submitted_application(): void
    {
        $this->login()->get('/download_application.php')->assertOk()->assertSee('Application must be submitted before downloading.');

        Application::create(['user_id' => $this->user->id, 'is_submitted' => 0, 'status' => 'draft']);
        $this->login()->get('/download_application')->assertSee('Application must be submitted before downloading.');
    }

    public function test_download_returns_a_zip_with_the_summary_pdf_and_documents(): void
    {
        $this->completeApplication();
        $this->upload(['document_1' => $this->pdf('photo.pdf')])->assertJson(['status' => 'success']);
        Application::query()->update(['is_submitted' => 1, 'status' => 'Pending']);

        $response = $this->login()->get('/download_application.php');
        $response->assertOk()
            ->assertHeader('Content-Type', 'application/zip')
            ->assertHeader('Content-Disposition', 'attachment; filename=Application_Package_Ann_Lee.zip');

        $zipPath = tempnam(sys_get_temp_dir(), 'pkg');
        file_put_contents($zipPath, $response->streamedContent());
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($zipPath));
        $names = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $names[] = $zip->getNameIndex($i);
        }
        $this->assertContains('Application_Summary.pdf', $names);
        $this->assertCount(2, $names);                                        // summary + the one existing upload
        $this->assertStringStartsWith('user_' . $this->user->id . '_doc_1_', collect($names)->first(fn ($n) => $n !== 'Application_Summary.pdf'));
        $this->assertStringStartsWith('%PDF-1.3', $zip->getFromName('Application_Summary.pdf'));
        $zip->close();
        @unlink($zipPath);

        $this->assertDirectoryDoesNotExist(storage_path('legacy/temp_zip/Ann_Lee'));   // working folder is removed
    }

    public function test_download_as_pdf_only_and_working_folders_are_removed(): void
    {
        $this->completeApplication();
        $this->upload(['document_1' => $this->pdf('photo.pdf')])->assertJson(['status' => 'success']);
        Application::query()->update(['is_submitted' => 1, 'status' => 'Pending']);
        \App\Models\PersonalInfo::query()->update(['city_of_birth' => 'Ürümqi']);   // accents must not break the PDF

        $response = $this->login()->get('/download_application?format=pdf');
        $response->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'attachment; filename=Application_Summary_Ann_Lee.pdf');
        $this->assertStringStartsWith('%PDF-1.3', $response->streamedContent());
        $this->assertSame([], glob(storage_path('legacy/temp_zip/Ann_Lee*')) ?: []);
    }

    public function test_applicant_can_open_only_their_own_uploaded_document(): void
    {
        $this->upload(['document_1' => $this->pdf('photo.pdf')])->assertJson(['status' => 'success']);
        $doc = \App\Models\Document::query()->where('user_id', $this->user->id)->firstOrFail();

        $this->login()->get('/my-document?id=' . $doc->id)->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->login()->get('/my-document?id=999999')->assertNotFound();
        $this->get('/my-document?id=' . $doc->id)->assertOk();   // still this user's session

        // Another signed-in applicant cannot open it.
        $this->withSession(['user_id' => $this->user->id + 500, 'full_name' => 'Other'])
            ->get('/my-document?id=' . $doc->id)->assertNotFound();
        // Signed out: sent to the login page.
        $this->flushSession();
        $this->get('/my-document?id=' . $doc->id)->assertRedirect(get_absolute_url('login'));
    }

    public function test_the_same_file_uploaded_again_for_the_same_document_is_kept_once(): void
    {
        $this->upload(['document_1' => $this->pdf('photo.pdf')])->assertJson(['status' => 'success']);
        // again (double click / retry), under another name too
        $this->upload(['document_1' => $this->pdf('photo.pdf')])
            ->assertExactJson(['status' => 'success', 'message' => 'This file is already uploaded. Nothing was added again.']);
        $this->upload(['document_1' => [$this->pdf('copy.pdf'), $this->pdf('new.pdf', 2)]])
            ->assertExactJson(['status' => 'success', 'message' => '1 file(s) uploaded successfully. 1 file(s) were already uploaded and were not added again.']);
        $this->assertSame(2, Document::query()->where('document_id', 1)->count());

        // The same file in ANOTHER document slot is a different upload and is kept.
        $this->upload(['document_7' => $this->pdf('photo.pdf')])->assertJson(['status' => 'success']);
        $this->assertSame(1, Document::query()->where('document_id', 7)->count());

        // Two identical files in one request: one copy.
        $this->upload(['document_4' => [$this->pdf('x.pdf', 3), $this->pdf('y.pdf', 3)]])->assertJson(['status' => 'success']);
        $this->assertSame(1, Document::query()->where('document_id', 4)->count());
    }

    public function test_a_four_megabyte_scan_is_accepted(): void
    {
        $this->upload(['document_7' => $this->pdf('passport-scan.pdf', 4096)])
            ->assertExactJson(['status' => 'success', 'message' => '1 file(s) uploaded successfully.']);
    }
}
