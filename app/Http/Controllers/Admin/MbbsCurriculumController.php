<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MbbsCurriculumController extends ContentCrudController
{
    protected string $table = 'mbbs_curriculum';
    protected string $slug = 'manage-mbbs-curriculum';
    protected string $pageTitle = 'MBBS Curriculum';
    protected string $pageSubtitle = 'Year-by-year structure shown on the MBBS page';
    protected string $view = 'admin.content.mbbs-curriculum';
    protected string $deletedMessage = 'Curriculum stage deleted.';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $year = $this->str($request, 'year_tag');
        $title = $this->str($request, 'title');
        if ($year === '' || $title === '') {
            return 'Year and title are required.';
        }
        $vals = ['year_tag' => $year, 'title' => $title, 'description' => $this->str($request, 'description'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Curriculum stage updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Curriculum stage added.';
    }
}
