<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;

class PortalDataTest extends PortalCase
{
    public function test_getters_answer_401_json_without_a_session(): void
    {
        foreach (['get_personal', 'get_education', 'get_documents'] as $name) {
            foreach (['/' . $name, '/' . $name . '.php'] as $uri) {
                $this->getJson($uri)->assertStatus(401)->assertExactJson(['status' => 'error', 'message' => 'Unauthorized']);
            }
        }
    }

    public function test_getters_report_empty_then_data_in_the_original_shape(): void
    {
        $this->login()->getJson('/get_personal.php')->assertOk()->assertExactJson(['status' => 'empty', 'data' => []]);
        $this->login()->getJson('/get_education.php')->assertOk()->assertExactJson(['status' => 'empty', 'data' => []]);
        $this->login()->getJson('/get_documents.php')->assertOk()->assertExactJson(['status' => 'empty', 'data' => []]);

        $this->completeApplication();

        $this->login()->getJson('/get_personal.php')->assertOk()
            ->assertJsonPath('status', 'success')->assertJsonPath('data.given_name', 'Ann')->assertJsonPath('data.user_id', $this->user->id);

        $edu = $this->login()->getJson('/get_education.php')->assertOk()->assertJsonPath('status', 'success')->json('data');
        $this->assertSame(['Highest', 'Other'], array_column($edu, 'entry_type'));

        $docs = $this->login()->getJson('/get_documents.php')->assertOk()->assertJsonPath('status', 'success')->json('data');
        $this->assertCount(7, $docs);
    }

    public function test_getters_only_return_the_current_users_rows(): void
    {
        $this->completeApplication();
        $other = \App\Models\User::create(['full_name' => 'B', 'email' => 'b@example.test', 'password' => 'x', 'role' => 'user', 'is_verified' => 1]);
        $this->login($other)->getJson('/get_personal')->assertExactJson(['status' => 'empty', 'data' => []]);
        $this->login($other)->getJson('/get_documents')->assertExactJson(['status' => 'empty', 'data' => []]);
    }

    public function test_status_endpoints_default_for_guests_and_follow_the_application_row(): void
    {
        $this->getJson('/get_application_status.php')->assertOk()->assertExactJson([
            'is_submitted' => false, 'personal_data' => false, 'has_any_data' => false, 'status' => 'Unsubmitted', 'admin_remark' => '',
        ]);
        $this->getJson('/get_submission_status.php')->assertOk()->assertExactJson(['is_submitted' => false]);

        $this->login()->getJson('/get_application_status')->assertJson(['has_any_data' => false, 'status' => 'Unsubmitted']);

        Application::create(['user_id' => $this->user->id, 'degree_applying_for' => 'Other', 'major_applying_for' => '', 'status' => 'draft']);
        $this->login()->getJson('/get_application_status')->assertJson(['is_submitted' => false, 'has_any_data' => true, 'personal_data' => false, 'status' => 'draft']);

        Application::query()->where('user_id', $this->user->id)->update(['is_submitted' => 1, 'status' => 'Rejected', 'admin_remark' => 'Blurry photo']);
        $this->login()->getJson('/get_application_status')->assertJson(['is_submitted' => true, 'status' => 'Rejected', 'admin_remark' => 'Blurry photo']);
        $this->login()->getJson('/get_submission_status')->assertExactJson(['is_submitted' => true]);
    }

    public function test_has_any_data_is_set_by_each_kind_of_data(): void
    {
        PersonalInfo::query()->insert(['user_id' => $this->user->id]);
        $this->login()->getJson('/get_application_status')->assertJson(['personal_data' => true, 'has_any_data' => true]);

        PersonalInfo::query()->delete();
        $this->login()->getJson('/get_application_status')->assertJson(['has_any_data' => false]);
        Document::query()->insert(['user_id' => $this->user->id, 'document_id' => 1, 'file_name' => 'a', 'file_path' => 'p']);
        $this->login()->getJson('/get_application_status')->assertJson(['personal_data' => false, 'has_any_data' => true]);
    }

    public function test_writers_reject_a_missing_or_wrong_csrf_token_with_the_original_403_json(): void
    {
        foreach (['save_personal', 'save_education', 'save_documents', 'save_application_program', 'save_data', 'submit_application'] as $name) {
            $this->login()->call('POST', '/' . $name . '.php', ['_token' => 'wrong'])
                ->assertStatus(403)
                ->assertExactJson(['status' => 'error', 'message' => 'Invalid or missing security token. Please refresh and try again.']);
        }
        // the header form used by assets/javascript/csrf.js is accepted
        $this->login()->call('POST', '/save_application_program', ['degree_applying_for' => 'x', 'major_applying_for' => 'y'], [], [], ['HTTP_X_CSRF_TOKEN' => self::TOKEN])
            ->assertJson(['status' => 'success']);
    }

    public function test_writers_answer_with_the_original_messages_when_logged_out(): void
    {
        $post = fn (string $u) => $this->withSession(['_token' => self::TOKEN])->call('POST', $u, ['_token' => self::TOKEN]);

        $post('/save_personal')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'User not logged in']);
        $post('/save_education')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'User not logged in']);
        $post('/save_data')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'User not logged in']);
        $post('/submit_application')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'User not logged in']);
        $post('/save_application_program')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'Not logged in']);
        $post('/save_documents')->assertOk()->assertExactJson(['status' => 'error', 'errors' => ['User not logged in.']]);
    }

    public function test_save_application_program_validates_trims_and_upserts(): void
    {
        $this->postJsonLogged('/save_application_program', ['degree_applying_for' => 'x'])
            ->assertExactJson(['status' => 'error', 'message' => 'Degree and Major are required.']);
        $this->postJsonLogged('/save_application_program', ['degree_applying_for' => '  ', 'major_applying_for' => 'y'])
            ->assertExactJson(['status' => 'error', 'message' => 'Degree and Major are required.']);

        $this->postJsonLogged('/save_application_program.php', ['degree_applying_for' => "Bachelor's Degree", 'major_applying_for' => ' MBBS '])
            ->assertExactJson(['status' => 'success', 'message' => 'Program choice saved successfully.']);
        $row = Application::query()->where('user_id', $this->user->id)->first();
        $this->assertSame("Bachelor's Degree", $row->degree_applying_for);
        $this->assertSame('MBBS', $row->major_applying_for);
        $this->assertSame('draft', $row->status);
        $this->assertEquals(0, $row->is_submitted);

        $this->postJsonLogged('/save_application_program', ['degree_applying_for' => 'Other', 'major_applying_for' => 'Nursing'])->assertJson(['status' => 'success']);
        $this->assertSame(1, Application::query()->where('user_id', $this->user->id)->count());
        $this->assertSame('Nursing', Application::query()->where('user_id', $this->user->id)->value('major_applying_for'));
    }

    public function test_save_personal_inserts_then_updates_and_keeps_empty_strings(): void
    {
        $this->postJsonLogged('/save_personal', $this->personalData())
            ->assertExactJson(['status' => 'success', 'message' => 'Personal details saved successfully.']);
        $row = PersonalInfo::query()->where('user_id', $this->user->id)->first();
        $this->assertSame('Ann', $row->given_name);
        $this->assertSame('', $row->chinese_name);           // '' stays '' (no ConvertEmptyStringsToNull)
        $this->assertNotNull($row->created_at);

        $this->postJsonLogged('/save_personal.php', $this->personalData(['given_name' => ' Anna ', 'passport_expiry' => '']))->assertJson(['status' => 'success']);
        $this->assertSame(1, PersonalInfo::query()->where('user_id', $this->user->id)->count());
        $row = PersonalInfo::query()->where('user_id', $this->user->id)->first();
        $this->assertSame(' Anna ', $row->given_name);         // not trimmed either
    }

    public function test_save_personal_stores_null_expiry_when_the_field_is_absent(): void
    {
        $data = $this->personalData();
        unset($data['passport_expiry']);
        $this->postJsonLogged('/save_personal', $data)->assertJson(['status' => 'success']);
        $this->assertNull(PersonalInfo::query()->where('user_id', $this->user->id)->value('passport_expiry'));
    }

    public function test_save_education_requires_the_first_two_blocks(): void
    {
        $message = fn (string $b) => $b . ' requires Education Level, Field of Study, Country, Institute Name, Qualification, and Year Attended (From/To).';

        $this->postJsonLogged('/save_education', ['education_level' => ['BA'], 'field_of_study' => ['x']])
            ->assertExactJson(['status' => 'error', 'message' => $message('Highest/Current Education')]);

        $data = $this->educationData();
        $data['institute_name'][1] = '   ';
        $this->postJsonLogged('/save_education', $data)->assertExactJson(['status' => 'error', 'message' => $message('Other Education')]);
        $this->assertSame(0, EducationRecord::query()->count());
    }

    public function test_save_education_replaces_all_rows_and_skips_empty_blocks(): void
    {
        EducationRecord::query()->insert(['user_id' => $this->user->id, 'entry_type' => 'Other', 'education_level' => 'old']);

        $this->postJsonLogged('/save_education.php', $this->educationData())
            ->assertExactJson(['status' => 'success', 'message' => 'Education details saved successfully.']);

        $rows = EducationRecord::query()->where('user_id', $this->user->id)->orderBy('id')->get();
        $this->assertSame(['Highest', 'Other'], $rows->pluck('entry_type')->all());
        $this->assertSame(['BSc', 'FSc'], $rows->pluck('education_level')->all());
        $this->assertNotNull($rows[0]->created_at);

        // a third filled block is stored as "Other"
        $data = $this->educationData();
        foreach (['education_level' => 'MSc', 'field_of_study' => 'Chem', 'institute_name' => 'Uni'] as $k => $v) {
            $data[$k][2] = $v;
        }
        $this->postJsonLogged('/save_education', $data)->assertJson(['status' => 'success']);
        $this->assertSame(['Highest', 'Other', 'Other'], EducationRecord::query()->where('user_id', $this->user->id)->orderBy('id')->pluck('entry_type')->all());
    }

    public function test_saving_is_refused_after_submission(): void
    {
        Application::create(['user_id' => $this->user->id, 'degree_applying_for' => 'a', 'major_applying_for' => 'b', 'is_submitted' => 1, 'status' => 'Pending']);

        $this->postJsonLogged('/save_personal', $this->personalData())->assertExactJson(['status' => 'error', 'message' => 'Application is submitted and cannot be edited']);
        $this->postJsonLogged('/save_education', $this->educationData())->assertExactJson(['status' => 'error', 'message' => 'Application is submitted and cannot be edited']);
        $this->postJsonLogged('/save_application_program', ['degree_applying_for' => 'x', 'major_applying_for' => 'y'])
            ->assertExactJson(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified.']);
        $this->postJsonLogged('/save_data', ['section' => 'personal_details'])
            ->assertExactJson(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified']);
        $this->postJsonLogged('/save_documents', [])->assertExactJson(['status' => 'error', 'errors' => ['Application is submitted and cannot be edited.']]);
        $this->assertSame(0, PersonalInfo::query()->count());
    }

    public function test_program_choice_is_locked_for_pending_and_rejected_status_even_if_not_flagged_submitted(): void
    {
        foreach (['Pending', 'Rejected'] as $status) {
            Application::query()->delete();
            Application::create(['user_id' => $this->user->id, 'degree_applying_for' => 'a', 'major_applying_for' => 'b', 'is_submitted' => 0, 'status' => $status]);
            $this->postJsonLogged('/save_application_program', ['degree_applying_for' => 'x', 'major_applying_for' => 'y'])
                ->assertExactJson(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified.']);
        }
    }

    public function test_writers_refuse_get_requests(): void
    {
        foreach (['save_personal', 'save_education', 'save_documents', 'save_application_program'] as $name) {
            $this->login()->getJson('/' . $name)->assertStatus(405)->assertJson(['status' => 'error']);
        }
        $this->assertSame(0, PersonalInfo::query()->count());
    }

    public function test_save_data_sections(): void
    {
        $this->postJsonLogged('/save_data', ['section' => 'nope'])
            ->assertExactJson(['status' => 'error', 'message' => 'Could not save your data. Please check your input and try again.']);
        $this->assertSame(0, Application::query()->count());   // rolled back / nothing created

        $this->postJsonLogged('/save_data.php', ['section' => 'personal_details', 'form_data' => ['given_name' => 'G', 'surname' => 'S', 'date_of_birth' => '2001-01-01', 'personal_tel' => '55']])
            ->assertExactJson(['status' => 'success', 'message' => 'Data saved successfully']);
        $p = PersonalInfo::query()->where('user_id', $this->user->id)->first();
        $this->assertSame(['G', 'S', '2001-01-01', '55'], [$p->given_name, $p->surname, $p->dob, $p->tel]);
        $this->assertSame('draft', Application::query()->where('user_id', $this->user->id)->value('status'));

        $this->postJsonLogged('/save_data', ['section' => 'education_background', 'form_data' => ['education_level' => 'BSc', 'institute_name' => 'I', 'year_from' => '2010', 'year_to' => '2012']])
            ->assertJson(['status' => 'success']);
        $e = EducationRecord::query()->where('user_id', $this->user->id)->first();
        $this->assertSame(['Highest', 'BSc', 'I'], [$e->entry_type, $e->education_level, $e->institute_name]);
        $this->assertSame(1, Application::query()->where('user_id', $this->user->id)->count());
    }

    public function test_save_data_get_reports_invalid_request(): void
    {
        $this->login()->getJson('/save_data')->assertExactJson(['status' => 'error', 'message' => 'Invalid request']);
    }

    private function postJsonLogged(string $uri, array $data = [])
    {
        return $this->login()->postJson2($uri, $data);
    }
}
