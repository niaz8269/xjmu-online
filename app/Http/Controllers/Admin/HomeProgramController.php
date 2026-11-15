<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeProgramController extends ContentCrudController
{
    protected string $table = 'home_programs';
    protected string $slug = 'manage-home-programs';
    protected string $pageTitle = 'Program Cards';
    protected string $pageSubtitle = 'Program cards in the homepage Programs section';
    protected string $view = 'admin.content.home-programs';
    protected string $deletedMessage = 'Program card deleted.';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $title = $this->str($request, 'title');
        if ($title === '') {
            return 'Title is required.';
        }
        $vals = ['title' => $title, 'description' => $this->str($request, 'description'), 'url' => $this->str($request, 'url'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Program card updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Program card added.';
    }
}
