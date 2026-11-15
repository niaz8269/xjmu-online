<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use App\Services\ApplicationPackage;
use App\Services\PdfFactory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** admin/applications, manage-applications, review_application, delete_application, update_personal, export_pdf, download_application */
class ApplicationsController extends AdminController
{
    use OpsHelpers;

    private const SUBMITTED_STATUSES = "'Pending','Approved','Rejected','Submitted'";

    /** GET /admin/applications */
    public function index(Request $request)
    {
        $S = self::SUBMITTED_STATUSES;
        try {
            $page = max(1, $this->intQuery($request, 'page', 1));
            $perPage = 20;
            $offset = ($page - 1) * $perPage;

            $search = trim($this->strQuery($request, 'search'));
            $filter = $this->strQuery($request, 'filter');
            if (! in_array($filter, ['submitted', 'unsubmitted', ''], true)) {
                $filter = '';
            }

            $applyFilters = function ($q) use ($search, $filter, $S) {
                if (! empty($search)) {
                    $term = "%{$search}%";
                    $q->whereRaw('(u.full_name LIKE ? OR u.email LIKE ?)', [$term, $term]);
                }
                if ($filter === 'submitted') {
                    $q->whereRaw("(a.is_submitted = 1 OR a.status IN ({$S}))");
                } elseif ($filter === 'unsubmitted') {
                    $q->whereRaw("(a.user_id IS NULL OR (COALESCE(a.is_submitted,0) != 1 AND (a.status IS NULL OR a.status NOT IN ({$S}))))");
                }
            };

            // Summary counts for filter buttons (always over the full dataset, ignoring search)
            $summary = (array) DB::table('users as u')
                ->leftJoin('applications as a', 'u.id', '=', 'a.user_id')
                ->selectRaw("SUM(CASE WHEN a.is_submitted = 1 OR a.status IN ({$S}) THEN 1 ELSE 0 END) AS submitted_count,
                    SUM(CASE WHEN a.user_id IS NULL OR (COALESCE(a.is_submitted,0) != 1 AND (a.status IS NULL OR a.status NOT IN ({$S}))) THEN 1 ELSE 0 END) AS unsubmitted_count,
                    COUNT(u.id) AS all_count")
                ->first();
            $countAll = (int) ($summary['all_count'] ?? 0);
            $countSubmitted = (int) ($summary['submitted_count'] ?? 0);
            $countUnsub = (int) ($summary['unsubmitted_count'] ?? 0);

            $base = DB::table('users as u')->leftJoin('applications as a', 'u.id', '=', 'a.user_id');
            $applyFilters($base);
            $totalRecords = (int) (clone $base)->count();
            $totalPages = (int) ceil($totalRecords / $perPage);

            $users = $this->rows($base->orderByDesc('u.created_at')->offset($offset)->limit($perPage)
                ->get(['u.id', 'u.full_name', 'u.email', 'a.is_submitted', 'a.submitted_at', 'a.status', 'a.admin_remark']));

            // Batch load the related data (no N+1)
            $userIds = array_column($users, 'id');
            $personalData = $educationData = $documentsData = $degreeMajorData = [];
            if (! empty($userIds)) {
                foreach ($this->rows(DB::table('personal_info')->whereIn('user_id', $userIds)->get()) as $row) {
                    $personalData[$row['user_id']] = $row;
                }
                foreach ($this->rows(DB::table('education_records')->whereIn('user_id', $userIds)->get()) as $row) {
                    $educationData[$row['user_id']][] = $row;
                }
                foreach ($this->rows(DB::table('documents')->whereIn('user_id', $userIds)->get()) as $row) {
                    $documentsData[$row['user_id']][] = $row;
                }
                try {
                    foreach ($this->rows(DB::table('applications')->whereIn('user_id', $userIds)
                        ->get(['user_id', 'degree_applying_for', 'major_applying_for'])) as $row) {
                        $degreeMajorData[$row['user_id']] = $row;
                    }
                } catch (QueryException $e) {
                    // Columns may not exist yet
                }
            }
        } catch (QueryException $e) {
            Log::error('Database error in applications.php: ' . $e->getMessage());
            return response(view('admin.applications.db_error')->render());
        }

        return $this->page('admin.applications.index', 'All Applications', 'applications', null, [
            'flash' => $this->pull('flash'),
            'search' => $search, 'filter' => $filter, 'page' => $page,
            'countAll' => $countAll, 'countSubmitted' => $countSubmitted, 'countUnsub' => $countUnsub,
            'totalRecords' => $totalRecords, 'totalPages' => $totalPages,
            'users' => $users, 'personalData' => $personalData, 'educationData' => $educationData,
            'documentsData' => $documentsData, 'degreeMajorData' => $degreeMajorData,
        ]);
    }

    /** GET|POST /admin/manage-applications */
    public function manage(Request $request)
    {
        $message = '';
        if ($request->isMethod('post') && $request->has('user_id')) {
            // (CSRF is enforced by Laravel's middleware; the original's "Security validation failed." check is covered there.)
            $userId = (int) $this->post($request, 'user_id');
            $status = trim($this->post($request, 'status'));
            try {
                // Update the remark ONLY on an existing application — never INSERT (audit #17).
                $n = DB::table('applications')->where('user_id', $userId)->update(['admin_remark' => $status]);
                $message = $n > 0
                    ? 'Remark updated successfully.'
                    : 'No application exists for this user, so nothing was updated.';
            } catch (QueryException $e) {
                $message = 'Failed to update remark. Please try again.';
            }
        }

        $perPage = 30;
        $page = $request->query('page');
        $page = (is_scalar($page) && is_numeric($page)) ? (int) $page : 1;
        if ($page < 1) {
            $page = 1;
        }

        $totalApplications = (int) DB::table('applications')->count();
        $totalPages = max(1, (int) ceil($totalApplications / $perPage));
        $offset = ($page - 1) * $perPage;

        $query = fn () => DB::table('applications as a')
            ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
            ->leftJoin('personal_info as p', 'p.user_id', '=', 'a.user_id')
            ->select('a.user_id as id', 'u.full_name', 'u.email', 'p.tel as phone', 'a.degree_applying_for',
                'a.major_applying_for', 'a.is_submitted', 'a.status', 'a.admin_remark');
        try {
            $applications = $this->rows($query()->orderByDesc('a.submitted_at')->orderByDesc('a.user_id')
                ->limit($perPage)->offset($offset)->get());
        } catch (QueryException $e) {
            $applications = $this->rows($query()->orderByDesc('a.user_id')->limit($perPage)->offset($offset)->get());
        }

        return $this->page('admin.applications.manage', 'Manage Applications', 'manage-applications', null, compact(
            'message', 'applications', 'page', 'totalPages', 'offset'
        ));
    }

    /** GET|POST /admin/review_application */
    public function review(Request $request)
    {
        $userId = (int) ($request->query('id') ?? $request->query('user_id') ?? 0);
        if (! $userId) {
            return $this->plain('Invalid user ID.');
        }

        if ($request->isMethod('post')) {
            $newStatus = $this->post($request, 'status');
            if (! in_array($newStatus, ['Pending', 'Approved', 'Rejected'], true)) {
                $newStatus = 'Pending';
            }
            $adminRemark = trim($this->post($request, 'admin_remark'));
            DB::table('applications')->where('user_id', $userId)->update(['status' => $newStatus, 'admin_remark' => $adminRemark]);

            session()->put('flash', 'Application has been updated successfully.');
            return redirect('/admin/applications');
        }

        $app = $this->row(DB::table('users')->join('applications', 'users.id', '=', 'applications.user_id')
            ->where('users.id', $userId)->select('users.full_name', 'users.email', 'applications.*')->first());
        if (! $app) {
            return $this->plain('Application not found.');
        }

        $personal = $this->row(DB::table('personal_info')->where('user_id', $userId)->first());
        $edu_records = $this->rows(DB::table('education_records')->where('user_id', $userId)->get());
        $documents = $this->rows(DB::table('documents')->where('user_id', $userId)->get());

        return $this->page('admin.applications.review', 'Review Application', 'applications', null,
            compact('app', 'personal', 'edu_records', 'documents'));
    }

    /** POST /admin/delete_application (anything else bounces back to the list) */
    public function destroy(Request $request)
    {
        if (! $request->isMethod('post')) {
            return redirect('/admin/applications');
        }
        if (! $request->has('user_id')) {
            return redirect('/admin/applications');
        }
        $userId = (int) $this->post($request, 'user_id');

        try {
            // Capture file paths before deleting the rows so the disk is cleaned AFTER the transaction commits.
            $filePaths = DB::table('documents')->where('user_id', $userId)->pluck('file_path')->all();

            // All-or-nothing: never leave a half-deleted application (audit #24).
            DB::transaction(function () use ($userId) {
                DB::table('personal_info')->where('user_id', $userId)->delete();
                DB::table('education_records')->where('user_id', $userId)->delete();
                DB::table('documents')->where('user_id', $userId)->delete();
                DB::table('applications')->where('user_id', $userId)->delete();
            });

            foreach ($filePaths as $relPath) {
                if ($relPath && ! str_contains($relPath, '..')) {
                    $path = public_path($relPath);
                    if (is_file($path)) {
                        @unlink($path);
                    }
                }
            }

            session()->put('flash', 'Application deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Delete application failed: ' . $e->getMessage());
            session()->put('flash', 'Unable to delete application at the moment. Please try again.');
        }
        return redirect('/admin/applications');
    }

    /** POST /admin/update_personal */
    public function updatePersonal(Request $request)
    {
        if (! $request->isMethod('post')) {
            return redirect('/admin/applications');
        }
        $userId = (int) $this->post($request, 'user_id', '0');
        if (! $userId) {
            return redirect('/admin/applications');
        }

        DB::table('personal_info')->where('user_id', $userId)->update([
            'given_name' => $request->input('given_name'),
            'surname' => $request->input('surname'),
            'dob' => $request->input('dob'),
        ]);

        session()->put('flash', 'Personal information updated.');
        return redirect('/admin/applications');
    }

    /** GET /admin/export_pdf?user_id= */
    public function exportPdf(Request $request, PdfFactory $pdfs)
    {
        if (! $request->query->has('user_id')) {
            return $this->plain('No user selected.');
        }
        $userId = (int) $this->strQuery($request, 'user_id');

        $user = DB::table('users')->where('id', $userId)->first();
        if (! $user) {
            return $this->plain('User not found.');
        }

        $personal = $this->row(DB::table('personal_info')->where('user_id', $userId)->first());
        $education = $this->rows(DB::table('education_records')->where('user_id', $userId)->get());
        $documents = $this->rows(DB::table('documents')->where('user_id', $userId)->get());

        $appProgram = null;
        try {
            $appProgram = $this->row(DB::table('applications')->where('user_id', $userId)
                ->first(['degree_applying_for', 'major_applying_for']));
        } catch (QueryException $e) {
        }

        $pdf = $pdfs->make();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Application Details', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);

        // Program Applying For (Degree / Major)
        $this->programBlock($pdf, $appProgram);

        // Personal Info Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Personal Information:', 0, 1);

        $pdf->SetFont('Arial', '', 12);
        if ($personal) {
            $this->personalLines($pdf, $personal);
        } else {
            $pdf->Cell(0, 8, 'No personal information found.', 0, 1);
        }

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Education Records:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        if ($education) {
            foreach ($education as $record) {
                $line = "{$record['entry_type']} - {$record['education_level']} in {$record['field_of_study']} from {$record['institute_name']} ({$record['from_date']} to {$record['to_date']})";
                $pdf->MultiCell(0, 8, $line);
            }
        } else {
            $pdf->Cell(0, 8, 'No education records found.', 0, 1);
        }

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Uploaded Documents:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        if ($documents) {
            foreach ($documents as $doc) {
                $pdf->Cell(0, 8, '- ' . $doc['file_name'], 0, 1);
            }
        } else {
            $pdf->Cell(0, 8, 'No documents uploaded.', 0, 1);
        }

        $name = "Application_User_{$userId}.pdf";
        return response($pdf->Output('S', $name), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $name . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    /** GET /admin/download_application?user_id= : PDF summary + the applicant's uploaded files, zipped (App\Services\ApplicationPackage). */
    public function download(Request $request, ApplicationPackage $package)
    {
        if (! $request->query->has('user_id')) {
            return $this->plain('User ID not provided.');
        }
        $userId = (int) $this->strQuery($request, 'user_id');

        try {
            $built = $package->build($userId);
        } catch (\RuntimeException $e) {
            return $this->plain($e->getMessage());
        } catch (\Throwable $e) {
            // Same friendly 500 + private log as the original fatal_guard()
            $log = storage_path('legacy/logs') . '/download_errors.log';
            if (! is_dir(dirname($log))) {
                @mkdir(dirname($log), 0755, true);
            }
            @error_log('[' . date('Y-m-d H:i:s') . '] Unhandled exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n", 3, $log);

            return $this->plain('Unable to generate this application package right now. Please try again shortly.', 500);
        }

        return new StreamedResponse(function () use ($built, $package) {
            readfile($built['zip']);
            $package->cleanup($built['folder']);
        }, 200, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename=' . $built['name'],
            'Content-Length' => (string) filesize($built['zip']),
        ]);
    }
    private function programBlock(\FPDF $pdf, ?array $appProgram): void
    {
        if ($appProgram && (! empty($appProgram['degree_applying_for']) || ! empty($appProgram['major_applying_for']))) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Program Applying For:', 0, 1);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(60, 8, 'Degree:', 0, 0);
            $pdf->Cell(0, 8, (string) ($appProgram['degree_applying_for'] ?? 'â€”'), 0, 1);
            $pdf->Cell(60, 8, 'Major:', 0, 0);
            $pdf->Cell(0, 8, (string) ($appProgram['major_applying_for'] ?? 'â€”'), 0, 1);
            $pdf->Ln(5);
        }
    }

    private function personalLines(\FPDF $pdf, array $personal): void
    {
        foreach ($personal as $key => $val) {
            if (! in_array($key, ['id', 'user_id', 'created_at'])) {
                $pdf->Cell(60, 8, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
                $pdf->Cell(0, 8, (string) $val, 0, 1);
            }
        }
    }
}
