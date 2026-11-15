<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScholarshipController extends ContentCrudController
{
    protected string $table = 'scholarships';
    protected string $slug = 'manage-scholarships';
    protected string $pageTitle = 'Scholarships';
    protected string $pageSubtitle = 'Scholarship offers listed on the public site';
    protected string $view = 'admin.content.scholarships';
    protected string $orderBy = 'sort_order, id DESC';
    protected string $deletedMessage = 'Scholarship deleted.';
    protected bool $genericDbError = true;

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $vals = [
            'name' => $this->str($request, 'name'), 'coverage' => $this->str($request, 'coverage'),
            'eligibility' => $this->str($request, 'eligibility'), 'description' => $this->str($request, 'description'),
            'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request),
        ];
        if ($vals['name'] === '') {
            return 'Scholarship name is required.';
        }
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Scholarship updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Scholarship added.';
    }
}
