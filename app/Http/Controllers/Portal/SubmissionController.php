<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Application status read-outs (get_application_status, get_submission_status) and final submission (submit_application). */
class SubmissionController extends Controller
{
    private const PERSONAL_REQUIRED = [
        'given_name' => 'Given Name',
        'surname' => 'Surname',
        'dob' => 'Date of Birth',
        'gender' => 'Gender',
        'marital_status' => 'Marital Status',
        'country_of_birth' => 'Country of Birth',
        'city_of_birth' => 'City of Birth',
        'religion' => 'Religion',
        'nationality' => 'Nationality',
        'native_language' => 'Native Language',
        'passport_no' => 'Passport No.',
        'present_address' => 'Present Address',
        'permanent_address' => 'Permanent Address',
        'tel' => 'Tel',
        'email' => 'Email',
        'emergency_contact_name' => 'Emergency Contact Name',
        'emergency_gender' => 'Emergency Gender',
        'emergency_relationship' => 'Emergency Relationship',
        'emergency_tel' => 'Emergency Tel',
        'emergency_email' => 'Emergency Email',
        'emergency_address' => 'Emergency Address',
    ];

    private const MANDATORY_DOCUMENTS = [
        1 => 'ID Photo',
        2 => 'Certificates of Highest Education',
        3 => 'Transcripts of Highest Education',
        4 => 'Study Plan',
        7 => 'Passport Home Page',
        8 => 'Physical Examination Record for Foreigner',
        15 => 'Non-Criminal Record Report',
    ];

    public function applicationStatus(Request $request): JsonResponse
    {
        $userId = $request->session()->get('user_id');

        $response = [
            'is_submitted' => false,
            'personal_data' => false,
            'has_any_data' => false,
            'status' => 'Unsubmitted',
            'admin_remark' => '',
        ];

        if ($userId) {
            $row = Application::query()->where('user_id', $userId)
                ->first(['is_submitted', 'status', 'admin_remark', 'degree_applying_for', 'major_applying_for']);
            if ($row) {
                $response['is_submitted'] = (bool) $row->is_submitted;
                $response['status'] = $row->status ?? 'Unsubmitted';
                $response['admin_remark'] = $row->admin_remark ?? '';

                if (! empty(trim((string) ($row->degree_applying_for ?? ''))) || ! empty(trim((string) ($row->major_applying_for ?? '')))) {
                    $response['has_any_data'] = true;
                }
            }

            $response['personal_data'] = PersonalInfo::query()->where('user_id', $userId)->exists();
            if ($response['personal_data']) {
                $response['has_any_data'] = true;
            }
            if (EducationRecord::query()->where('user_id', $userId)->exists()
                || Document::query()->where('user_id', $userId)->exists()) {
                $response['has_any_data'] = true;
            }
        }

        return response()->json($response);
    }

    public function submissionStatus(Request $request): JsonResponse
    {
        $userId = $request->session()->get('user_id');
        if (! $userId) {
            return response()->json(['is_submitted' => false]);
        }

        $flag = Application::query()->where('user_id', $userId)->value('is_submitted');

        return response()->json(['is_submitted' => $flag !== null && (int) $flag === 1]);
    }

    public function submit(Request $request): JsonResponse
    {
        if ($request->method() !== 'POST') {
            return response()->json(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $userId = $request->session()->get('user_id');
        if (! $userId) {
            return response()->json(['status' => 'error', 'message' => 'User not logged in']);
        }

        $application = Application::query()->where('user_id', $userId)->first();
        if ($application && ! empty($application->is_submitted)) {
            return $this->error('Application already submitted');
        }

        // Program choice
        if (! $application
            || trim((string) ($application->degree_applying_for ?? '')) === ''
            || trim((string) ($application->major_applying_for ?? '')) === '') {
            return $this->error('Please complete Degree and Major in the application program section.');
        }

        // Mandatory personal fields
        $personal = PersonalInfo::query()->where('user_id', $userId)->first();
        if (! $personal) {
            return $this->error('Please complete Personal Details section before submission.');
        }
        $missing = [];
        foreach (self::PERSONAL_REQUIRED as $field => $label) {
            if (trim((string) ($personal->{$field} ?? '')) === '') {
                $missing[] = $label;
            }
        }
        if ($missing) {
            return $this->error('Please complete required personal fields: ' . implode(', ', $missing));
        }

        // Mandatory education blocks (Highest/Current + first Other)
        if (! $this->hasCompleteEducation($userId, 'Highest')) {
            return $this->error('Please complete all mandatory fields in Highest/Current Education section.');
        }
        if (! $this->hasCompleteEducation($userId, 'Other')) {
            return $this->error('Please complete all mandatory fields in Other Education section.');
        }

        // Mandatory documents
        $missingDocs = [];
        foreach (self::MANDATORY_DOCUMENTS as $docId => $name) {
            if (! Document::query()->where('user_id', $userId)->where('document_id', $docId)->exists()) {
                $missingDocs[] = $name;
            }
        }
        if ($missingDocs) {
            return $this->error('Please upload all mandatory documents: ' . implode(', ', $missingDocs));
        }

        Application::query()->where('user_id', $userId)->update([
            'is_submitted' => 1, 'submitted_at' => now(), 'status' => 'Pending', 'updated_at' => now(),
        ]);

        \App\Services\ApplicationLog::status((int) $userId, 'draft', 'Pending', 'Application submitted by the applicant.');
        app(\App\Services\StudentNotifier::class)->submitted((int) $userId);

        return response()->json(['status' => 'success', 'message' => 'Application submitted successfully']);
    }

    private function error(string $message): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message]);
    }

    private function hasCompleteEducation($userId, string $entryType): bool
    {
        $query = EducationRecord::query()->where('user_id', $userId)->where('entry_type', $entryType)
            ->whereNotNull('from_date')->whereNotNull('to_date');
        foreach (['education_level', 'field_of_study', 'country', 'institute_name', 'qualification'] as $column) {
            $query->whereRaw("TRIM(COALESCE({$column}, '')) <> ''");
        }

        return $query->exists();
    }
}
