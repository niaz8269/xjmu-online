<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * save_data: the original "legacy monolithic save endpoint" (still called by apply.js). Sections: personal_details,
 * education_background, documents. Plain inserts, as before: personal_info/education_records have no unique key on
 * user_id, so the original ON DUPLICATE KEY clause never fired for them.
 */
class SaveDataController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('user_id');

        // Block if the application has already been submitted
        try {
            $flag = Application::query()->where('user_id', $userId)->value('is_submitted');
            if (! empty($flag)) {
                return response()->json(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // applications table may not exist yet; continue
        }

        $response = ['status' => 'error', 'message' => 'Invalid request'];

        if ($request->method() === 'POST') {
            error_log('save_data.php POST request received');

            $section = $request->input('section', '');
            $form = $request->input('form_data', []);
            $form = is_array($form) ? $form : [];

            DB::beginTransaction();
            try {
                switch ($section) {
                    case 'personal_details':
                        $this->savePersonal($userId, $form);
                        break;
                    case 'education_background':
                        $this->saveEducation($userId, $form);
                        break;
                    case 'documents':
                        $this->saveDocument($request, $userId, $form);
                        break;
                    default:
                        throw new \Exception('Invalid section');
                }

                // Upsert draft application row
                if (Application::query()->where('user_id', $userId)->exists()) {
                    Application::query()->where('user_id', $userId)->update(['updated_at' => now()]);
                } else {
                    Application::query()->insert(['user_id' => $userId, 'status' => 'draft', 'updated_at' => now()]);
                }

                DB::commit();
                $response = ['status' => 'success', 'message' => 'Data saved successfully'];
            } catch (\Exception $e) {
                DB::rollBack();
                // Log the detail server-side; return a generic message
                error_log('save_data.php error: ' . $e->getMessage());
                $response = ['status' => 'error', 'message' => 'Could not save your data. Please check your input and try again.'];
            }
        }

        return response()->json($response);
    }

    private function savePersonal(int $userId, array $f): void
    {
        PersonalInfo::query()->insert([
            'user_id' => $userId,
            'given_name' => $f['given_name'] ?? '',
            'surname' => $f['surname'] ?? '',
            'dob' => $f['date_of_birth'] ?? ($f['dob'] ?? ''),
            'gender' => $f['gender'] ?? '',
            'marital_status' => $f['marital_status'] ?? '',
            'country_of_birth' => $f['country_of_birth'] ?? '',
            'city_of_birth' => $f['city_of_birth'] ?? '',
            'religion' => $f['religion'] ?? '',
            'nationality' => $f['nationality'] ?? '',
            'native_language' => $f['native_language'] ?? '',
            'passport_no' => $f['passport_no'] ?? '',
            'passport_expiry' => $f['passport_expiry'] ?? null,
            'tel' => $f['personal_tel'] ?? ($f['tel'] ?? ''),
            'email' => $f['personal_email'] ?? ($f['email'] ?? ''),
            'alternate_email' => $f['alternate_email'] ?? '',
            'wechat_id' => $f['wechat_id'] ?? '',
            'skype_no' => $f['skype_no'] ?? '',
            'present_address' => $f['personal_address'] ?? ($f['present_address'] ?? ''),
            'permanent_address' => $f['permanent_address'] ?? ($f['personal_address'] ?? ''),
            'emergency_contact_name' => $f['emergency_contact_name'] ?? '',
            'emergency_gender' => $f['emergency_contact_gender'] ?? ($f['emergency_gender'] ?? ''),
            'emergency_relationship' => $f['relationship'] ?? ($f['emergency_relationship'] ?? ''),
            'emergency_tel' => $f['emergency_tel'] ?? '',
            'emergency_email' => $f['emergency_email'] ?? '',
            'emergency_address' => $f['emergency_address'] ?? '',
            'created_at' => now(),
        ]);
    }

    private function saveEducation(int $userId, array $f): void
    {
        EducationRecord::query()->insert([
            'user_id' => $userId,
            'entry_type' => 'Highest',
            'education_level' => $f['education_level'] ?? '',
            'field_of_study' => $f['field_of_study'] ?? '',
            'country' => $f['country_of_institute'] ?? ($f['country'] ?? ''),
            'institute_name' => $f['institute_name'] ?? '',
            'qualification' => $f['qualification'] ?? '',
            'from_date' => $f['year_from'] ?? ($f['from_date'] ?? ''),
            'to_date' => $f['year_to'] ?? ($f['to_date'] ?? ''),
            'created_at' => now(),
        ]);
    }

    private function saveDocument(Request $request, int $userId, array $f): void
    {
        $file = $request->file('document_file');
        if (! $file || is_array($file) || ! $file->isValid()) {
            throw new \Exception('No file uploaded or upload error');
        }

        $documentId = (int) ($f['document_id'] ?? 0);
        $fileName = basename($file->getClientOriginalName());
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (! in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], true)) {
            throw new \Exception('File type not allowed');
        }

        // Size cap (10 MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \Exception('File too large (max 10 MB)');
        }

        // Verify the real MIME type, not just the extension
        $allowedMime = [
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg', 'image/png',
        ];
        $detected = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getPathname());
        if (! in_array($detected, $allowedMime, true)) {
            throw new \Exception('File content does not match an allowed type');
        }

        $uploadDir = public_path('uploads/admissions');
        if (! is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }
        $uniqueName = 'user_' . $userId . '_doc_' . $documentId . '_' . time() . '.' . $ext;

        try {
            $file->move($uploadDir, $uniqueName);
        } catch (\Throwable $e) {
            throw new \Exception('Failed to save uploaded file');
        }

        Document::query()->insert([
            'user_id' => $userId,
            'document_id' => $documentId,
            'file_name' => $fileName,
            'file_path' => 'uploads/admissions/' . $uniqueName,
            'uploaded_on' => now(),
        ]);
    }
}
