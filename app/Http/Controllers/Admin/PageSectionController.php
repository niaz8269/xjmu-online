<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** /admin/manage-pages — text sections (page_sections) of the main public pages, edited with Quill. */
class PageSectionController extends ContentCrudController
{
    protected string $table = 'page_sections';
    protected string $slug = 'manage-pages';
    protected string $pageTitle = 'Page Content';
    protected string $pageSubtitle = 'Edit the text sections of your main pages';
    protected string $view = 'admin.content.pages';
    protected string $deletedMessage = 'Section deleted.';
    protected bool $genericDbError = true;

    private const PAGES = ['about' => 'About', 'admissions' => 'Admissions', 'mbbs' => 'MBBS Program', 'fees' => 'Fees', 'support' => 'Support', 'scholarships' => 'Scholarships'];

    private function pageKey(Request $request, ?array $edit = null): string
    {
        $key = (string) $request->query('page', 'about');
        if (! isset(self::PAGES[$key])) {
            $key = 'about';
        }
        if ($edit) {
            $key = $edit['page_key'];
        }

        return $key;
    }

    protected function viewData(Request $request, bool $isForm, ?array $edit): array
    {
        return ['pagesAvailable' => self::PAGES, 'page_key' => $this->pageKey($request, $edit)];
    }

    protected function listRows(Request $request): array
    {
        return $this->arrays(DB::table($this->table)->where('page_key', $this->pageKey($request))->orderByRaw('sort_order, id')->get());
    }

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $pk = (string) ($request->post('page_key') ?? 'about');
        $section = $this->str($request, 'section_key');
        $heading = $this->str($request, 'heading');
        $body = (string) ($request->post('body') ?? '');   // rich HTML from Quill
        if ($section === '') {
            $section = 'section-' . substr(md5($heading . microtime()), 0, 6);
        }
        $vals = ['section_key' => $section, 'heading' => $heading, 'body' => $body,
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Section saved.';
        }
        DB::table($this->table)->insert(['page_key' => $pk] + $vals);
        return 'Section added.';
    }

    protected function redirectTo(Request $request): string
    {
        return '/admin/manage-pages?page=' . urlencode((string) ($request->post('page_key') ?? 'about'));
    }
}
