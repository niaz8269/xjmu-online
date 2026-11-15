<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Title + description + sort + published items (home steps / why-us / accreditations). One shared view. */
abstract class SimpleItemController extends ContentCrudController
{
    protected string $view = 'admin.content.home-item';
    protected string $deletedMessage = 'Item deleted.';
    protected string $addLabel;

    protected function viewData(Request $request, bool $isForm, ?array $edit): array
    {
        return ['slug' => $this->slug, 'addLabel' => $this->addLabel];
    }

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $title = $this->str($request, 'title');
        if ($title === '') {
            return 'Title is required.';
        }
        $vals = ['title' => $title, 'description' => $this->str($request, 'description'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Item updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Item added.';
    }
}
