<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Document;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Shared setup for the applicant-portal feature tests: a verified user, a logged-in session, CSRF header. */
abstract class PortalCase extends TestCase
{
    use RefreshDatabase;

    protected const TOKEN = 'portal-test-token';

    protected User $user;

    /** @var list<string> uploaded files (relative to public/) to remove after each test */
    protected array $uploaded = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'full_name' => 'Ann <Lee>', 'email' => 'ann@example.test',
            'password' => password_hash('Passw0rd!', PASSWORD_DEFAULT), 'role' => 'user', 'is_verified' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        foreach (Document::query()->pluck('file_path') as $path) {
            @unlink(public_path($path));
        }
        @rmdir(public_path('uploads/admissions'));
        @rmdir(public_path('uploads/videos'));
        parent::tearDown();
    }

    /** Session of the logged-in applicant (what the login controller stores). */
    protected function login(?User $user = null): static
    {
        $user ??= $this->user;

        return $this->withSession([
            'user_id' => $user->id, 'full_name' => $user->full_name, 'role' => $user->role, '_token' => self::TOKEN,
        ]);
    }

    protected function postJson2(string $uri, array $data = [], array $files = [])
    {
        return $this->call('POST', $uri, $data + ['_token' => self::TOKEN], [], $files, ['HTTP_ACCEPT' => 'application/json']);
    }

    protected function personalData(array $over = []): array
    {
        return $over + [
            'given_name' => 'Ann', 'surname' => 'Lee', 'chinese_name' => '', 'dob' => '2000-01-02', 'gender' => 'Female',
            'marital_status' => 'Single', 'country_of_birth' => 'PK', 'city_of_birth' => 'Lahore', 'religion' => 'X',
            'nationality' => 'PK', 'native_language' => 'Urdu', 'passport_no' => 'AB123', 'passport_expiry' => '2030-01-01',
            'present_address' => 'addr 1', 'permanent_address' => 'addr 2', 'tel' => '123', 'email' => 'a@b.co',
            'alternate_email' => '', 'wechat_id' => '', 'skype_no' => '', 'emergency_contact_name' => 'Bob',
            'emergency_gender' => 'Male', 'emergency_relationship' => 'Dad', 'emergency_tel' => '999',
            'emergency_email' => 'b@c.co', 'emergency_address' => 'addr 3',
        ];
    }

    protected function educationData(): array
    {
        return [
            'education_level' => ['BSc', 'FSc', ''], 'field_of_study' => ['Bio', 'Sci', ''], 'country' => ['PK', 'PK', ''],
            'institute_name' => ['UoL', 'College', ''], 'qualification' => ['Degree', 'Diploma', ''],
            'from_date' => ['2015-01-01', '2013-01-01', ''], 'to_date' => ['2019-01-01', '2015-01-01', ''],
        ];
    }

    /** Fill everything a submission needs, directly in the database. */
    protected function completeApplication(): void
    {
        Application::create(['user_id' => $this->user->id, 'degree_applying_for' => "Bachelor's Degree", 'major_applying_for' => 'MBBS', 'is_submitted' => 0, 'status' => 'draft']);
        PersonalInfo::query()->insert($this->personalData() + ['user_id' => $this->user->id]);
        foreach ([['Highest', 'BSc'], ['Other', 'FSc']] as [$type, $level]) {
            \App\Models\EducationRecord::query()->insert([
                'user_id' => $this->user->id, 'entry_type' => $type, 'education_level' => $level, 'field_of_study' => 'x',
                'country' => 'PK', 'institute_name' => 'I', 'qualification' => 'Q', 'from_date' => '2010-01-01', 'to_date' => '2012-01-01',
            ]);
        }
        foreach ([1, 2, 3, 4, 7, 8, 15] as $id) {
            Document::query()->insert(['user_id' => $this->user->id, 'document_id' => $id, 'file_name' => "d{$id}.pdf", 'file_path' => "uploads/admissions/none_{$id}.pdf"]);
        }
    }
}
