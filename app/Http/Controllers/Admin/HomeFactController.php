<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeFactController extends ContentCrudController
{
    protected string $table = 'home_facts';
    protected string $slug = 'manage-home-facts';
    protected string $pageTitle = 'MBBS Facts';
    protected string $pageSubtitle = 'Quick facts in the homepage MBBS section';
    protected string $view = 'admin.content.home-facts';
    protected string $deletedMessage = 'Fact deleted.';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $label = $this->str($request, 'label');
        if ($label === '') {
            return 'Label is required.';
        }
        $vals = ['label' => $label, 'value' => $this->str($request, 'value'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Fact updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Fact added.';
    }
}
