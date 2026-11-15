<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends ContentCrudController
{
    protected string $table = 'programs';
    protected string $slug = 'manage-programs';
    protected string $pageTitle = 'Programs';
    protected string $pageSubtitle = 'Courses/programs listed on the public site';
    protected string $view = 'admin.content.programs';
    protected string $orderBy = 'sort_order, id DESC';
    protected string $deletedMessage = 'Program deleted.';
    protected bool $genericDbError = true;

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $posted = $request->post('category');
        $cat = in_array($posted, ['bachelor', 'master', 'doctoral', 'non-degree', 'associate'], true) ? $posted : 'bachelor';
        $vals = [
            'name' => $this->str($request, 'name'), 'degree' => $this->str($request, 'degree'), 'category' => $cat,
            'duration' => $this->str($request, 'duration'), 'language' => $this->str($request, 'language'),
            'tuition' => $this->str($request, 'tuition'), 'description' => $this->str($request, 'description'),
            'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request),
        ];
        if ($vals['name'] === '') {
            return 'Program name is required.';
        }
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Program updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Program added.';
    }
}
