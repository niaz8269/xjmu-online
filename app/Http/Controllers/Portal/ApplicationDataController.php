<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;
use App\Services\DocumentUploader;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** JSON endpoints behind the application form: get_personal / get_education / get_documents and the save_* writers. */
class ApplicationDataController extends Controller
{
    private const PERSONAL_FIELDS = [
        'given_name', 'surname', 'chinese_name', 'dob', 'gender', 'marital_status', 'country_of_birth', 'city_of_birth',
        'religion', 'nationality', 'native_language', 'passport_no', 'passport_expiry', 'present_address',
        'permanent_address', 'tel', 'email', 'alternate_email', 'wechat_id', 'skype_no', 'emergency_contact_name',
        'emergency_gender', 'emergency_relationship', 'emergency_tel', 'emergency_email', 'emergency_address',
    ];

    // ---- readers ------------------------------------------------------------------------------------------------

    public function getPersonal(Request $request): JsonResponse
    {
        try {
            $row = PersonalInfo::query()->where('user_id', $request->session()->get('user_id'))->first();

            return $row
                ? response()->json(['status' => 'success', 'data' => $row->getAttributes()])
                : response()->json(['status' => 'empty', 'data' => []]);
        } catch (\Exception $e) {
            error_log('get_personal.php error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to load personal information'], 500);
        }
    }

    public function getEducation(Request $request): JsonResponse
    {
        try {
            $rows = EducationRecord::query()
                ->where('user_id', $request->session()->get('user_id'))
                ->orderBy('entry_type')->orderBy('id')
                ->get()->map(fn ($r) => $r->getAttributes())->all();

            return $rows
                ? response()->json(['status' => 'success', 'data' => $rows])
                : response()->json(['status' => 'empty', 'data' => []]);
        } catch (\Exception $e) {
            error_log('get_education.php error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to load education records'], 500);
        }
    }

    public function getDocuments(Request $request): JsonResponse
    {
        try {
            $rows = Document::query()
                ->where('user_id', $request->session()->get('user_id'))
                ->get()->map(fn ($r) => $r->getAttributes())->all();

            return $rows
                ? response()->json(['status' => 'success', 'data' => $rows])
                : response()->json(['status' => 'empty', 'data' => []]);
        } catch (\Exception $e) {
            error_log('get_documents.php error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to load documents'], 500);
        }
    }

    // ---- writers ------------------------------------------------------------------------------------------------

    public function savePersonal(Request $request): JsonResponse
    {
        if ($bad = $this->notPost($request)) {
            return $bad;
        }
        $userId = $request->session()->get('user_id');
        if ($this->isSubmitted($userId)) {
            return response()->json(['status' => 'error', 'message' => 'Application is submitted and cannot be edited']);
        }

        $data = [];
        foreach (self::PERSONAL_FIELDS as $field) {
            $data[$field] = $request->input($field, $field === 'passport_expiry' ? null : '');
        }

        try {
            if (PersonalInfo::query()->where('user_id', $userId)->exists()) {
                PersonalInfo::query()->where('user_id', $userId)->update($data);
            } else {
                PersonalInfo::query()->insert($data + ['user_id' => $userId, 'created_at' => now()]);
            }

            return response()->json(['status' => 'success', 'message' => 'Personal details saved successfully.']);
        } catch (\Exception $e) {
            error_log('save_personal: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to save personal details']);
        }
    }

    public function saveEducation(Request $request): JsonResponse
    {
        if ($bad = $this->notPost($request)) {
            return $bad;
        }
        $userId = $request->session()->get('user_id');
        if ($this->isSubmitted($userId)) {
            return response()->json(['status' => 'error', 'message' => 'Application is submitted and cannot be edited']);
        }

        try {
            $levels = $request->input('education_level', []);
            $fields = $request->input('field_of_study', []);
            $countries = $request->input('country', []);
            $institutes = $request->input('institute_name', []);
            $qualifications = $request->input('qualification', []);
            $fromDates = $request->input('from_date', []);
            $toDates = $request->input('to_date', []);

            // First two blocks (Highest/Current + Other) are mandatory
            foreach ([0, 1] as $idx) {
                $values = [
                    trim((string) ($levels[$idx] ?? '')), trim((string) ($fields[$idx] ?? '')),
                    trim((string) ($countries[$idx] ?? '')), trim((string) ($institutes[$idx] ?? '')),
                    trim((string) ($qualifications[$idx] ?? '')), trim((string) ($fromDates[$idx] ?? '')),
                    trim((string) ($toDates[$idx] ?? '')),
                ];
                if (in_array('', $values, true)) {
                    $blockName = $idx === 0 ? 'Highest/Current Education' : 'Other Education';
                    return response()->json([
                        'status' => 'error',
                        'message' => $blockName . ' requires Education Level, Field of Study, Country, Institute Name, Qualification, and Year Attended (From/To).',
                    ]);
                }
            }

            EducationRecord::query()->where('user_id', $userId)->delete();

            $entryTypes = ['Highest', 'Other', 'Other'];
            for ($i = 0; $i < count($levels); $i++) {
                if (empty($levels[$i]) && empty($fields[$i]) && empty($institutes[$i])) {
                    continue;
                }
                EducationRecord::query()->insert([
                    'user_id' => $userId,
                    'entry_type' => $entryTypes[$i] ?? 'Other',
                    'education_level' => $levels[$i] ?? null,
                    'field_of_study' => $fields[$i] ?? null,
                    'country' => $countries[$i] ?? null,
                    'institute_name' => $institutes[$i] ?? null,
                    'qualification' => $qualifications[$i] ?? null,
                    'from_date' => $fromDates[$i] ?? null,
                    'to_date' => $toDates[$i] ?? null,
                    'created_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Education details saved successfully.']);
        } catch (\Exception $e) {
            error_log('save_education: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to save education details']);
        }
    }

    public function saveDocuments(Request $request, DocumentUploader $uploader): JsonResponse
    {
        if ($request->method() !== 'POST') {
            return response()->json(['status' => 'error', 'errors' => ['Invalid request method']], 405);
        }
        $userId = $request->session()->get('user_id');
        if (! $userId) {
            return response()->json(['status' => 'error', 'errors' => ['User not logged in.']]);
        }
        if ($this->isSubmitted($userId)) {
            return response()->json(['status' => 'error', 'errors' => ['Application is submitted and cannot be edited.']]);
        }

        $files = $uploader->collect($request);
        if (! $files['hasFiles']) {
            return response()->json(['status' => 'error', 'errors' => ['Please select at least one file before clicking Verify and Save.']]);
        }

        try {
            $result = $uploader->store((int) $userId, $files['entries']);
        } catch (\Exception $e) {
            $uploader->log('Unhandled upload exception: ' . $e->getMessage(), (int) $userId);
            return response()->json(['status' => 'error', 'errors' => ['Upload failed due to a server error. Please try again.']]);
        }

        if (! empty($result['errors'])) {
            $request->session()->put('upload_errors', $result['errors']);
            return response()->json(['status' => 'error', 'errors' => $result['errors']]);
        }
        if ($result['success'] > 0) {
            return response()->json(['status' => 'success', 'message' => $result['success'] . ' file(s) uploaded successfully.']);
        }
        return response()->json(['status' => 'error', 'errors' => ['No files were processed. Please check selected files and try again.']]);
    }

    public function saveApplicationProgram(Request $request): JsonResponse
    {
        if ($bad = $this->notPost($request)) {
            return $bad;
        }
        $userId = $request->session()->get('user_id');
        if (! $userId) {
            return response()->json(['status' => 'error', 'message' => 'Not logged in']);
        }

        $degree = trim((string) $request->input('degree_applying_for', ''));
        $major = trim((string) $request->input('major_applying_for', ''));
        if ($degree === '' || $major === '') {
            return response()->json(['status' => 'error', 'message' => 'Degree and Major are required.']);
        }

        try {
            $row = Application::query()->where('user_id', $userId)->first();
            if ($row && ((int) ($row->is_submitted ?? 0) === 1 || in_array($row->status ?? '', ['Pending', 'Rejected']))) {
                return response()->json(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified.']);
            }

            if ($row) {
                Application::query()->where('user_id', $userId)->update([
                    'degree_applying_for' => $degree, 'major_applying_for' => $major, 'updated_at' => now(),
                ]);
            } else {
                Application::query()->insert([
                    'user_id' => $userId, 'degree_applying_for' => $degree, 'major_applying_for' => $major,
                    'is_submitted' => 0, 'status' => 'draft', 'updated_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Program choice saved successfully.']);
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'degree_applying_for') || str_contains($e->getMessage(), 'Unknown column')) {
                return response()->json(['status' => 'error', 'message' => 'Please run install_application_degree_major.sql on your database first.']);
            }
            error_log('save_application_program: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to save. Please try again.']);
        }
    }

    // ---- helpers ------------------------------------------------------------------------------------------------

    /** The original scripts ran their save logic for any method; a GET with no form data would blank the record, so refuse it. */
    private function notPost(Request $request): ?JsonResponse
    {
        return $request->method() === 'POST'
            ? null
            : response()->json(['status' => 'error', 'message' => 'Invalid request method'], 405);
    }

    private function isSubmitted($userId): bool
    {
        $flag = Application::query()->where('user_id', $userId)->value('is_submitted');
        return ! empty($flag);
    }
}
