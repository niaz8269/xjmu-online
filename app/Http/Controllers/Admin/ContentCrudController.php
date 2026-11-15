<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Shared flow of the "content" admin screens (list / ?action=new / ?action=edit&id=N, POST save or _do=delete,
 * flash message in the session, redirect back to the list). Subclasses supply the table, titles and persist().
 * Views stay per page (identical HTML to the originals).
 */
abstract class ContentCrudController extends AdminController
{
    /** Table name. */
    protected string $table;
    /** URL slug under /admin (also the menu key), e.g. 'manage-faqs'. */
    protected string $slug;
    protected string $pageTitle;
    protected string $pageSubtitle;
    protected string $view;
    /** Raw ORDER BY of the list. */
    protected string $orderBy = 'sort_order, id';
    /** Flash shown after a delete. */
    protected string $deletedMessage = 'Deleted.';
    /** Originals of faqs/programs/scholarships/pages caught only PDOException and showed a generic message. */
    protected bool $genericDbError = false;

    /** Save (insert/update) from the POST body; return the flash message. May throw. */
    abstract protected function persist(Request $request): string;

    /** Extra data for the view. */
    protected function viewData(Request $request, bool $isForm, ?array $edit): array
    {
        return [];
    }

    /** Rows of the list view. */
    protected function listRows(Request $request): array
    {
        return $this->arrays(DB::table($this->table)->orderByRaw($this->orderBy)->get());
    }

    public function index(Request $request)
    {
        $action = (string) $request->query('action', 'list');
        $flash = (string) $request->session()->pull('flash', '');

        $edit = null;
        if ($action === 'edit' && $request->query->has('id')) {
            $edit = $this->find((int) $request->query('id'));
        }
        $isForm = ($action === 'edit' || $action === 'new');
        $rows = $isForm ? [] : $this->listRows($request);

        return $this->page($this->view, $this->pageTitle, $this->slug, $this->pageSubtitle,
            compact('flash', 'edit', 'isForm', 'rows') + $this->viewData($request, $isForm, $edit));
    }

    public function store(Request $request)
    {
        $early = $this->beforeSave($request);
        if ($early !== null) {
            return $early;
        }
        try {
            $flash = $request->post('_do') === 'delete' ? $this->delete($request) : $this->persist($request);
        } catch (QueryException $e) {
            Log::error($this->slug . ': ' . $e->getMessage());
            $flash = $this->genericDbError ? 'Database error. Please try again.' : 'Error: ' . $this->dbMessage($e);
        } catch (\Throwable $e) {
            if ($this->genericDbError) {
                throw $e;
            }
            Log::error($this->slug . ': ' . $e->getMessage());
            $flash = 'Error: ' . $e->getMessage();
        }
        $request->session()->put('flash', $flash);

        return redirect($this->redirectTo($request));
    }

    /** Hook for pre-save short-circuits (e.g. AJAX reorder). Return a response to stop. */
    protected function beforeSave(Request $request)
    {
        return null;
    }

    protected function redirectTo(Request $request): string
    {
        return '/admin/' . $this->slug;
    }

    protected function delete(Request $request): string
    {
        DB::table($this->table)->where('id', (int) $request->post('id'))->delete();

        return $this->deletedMessage;
    }

    protected function find(int $id): ?array
    {
        $row = DB::table($this->table)->where('id', $id)->first();

        return $row ? (array) $row : null;
    }

    protected function arrays($rows): array
    {
        return collect($rows)->map(fn ($r) => (array) $r)->all();
    }

    protected function str(Request $request, string $key): string
    {
        return trim((string) ($request->post($key) ?? ''));
    }

    protected function int(Request $request, string $key): int
    {
        return (int) ($request->post($key) ?? 0);
    }

    protected function published(Request $request): int
    {
        return $request->request->has('is_published') ? 1 : 0;
    }

    /** PDO-style message (no SQL appended) like the originals' $e->getMessage(). */
    protected function dbMessage(QueryException $e): string
    {
        return $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage();
    }

    /** Unlink an uploaded file (web-relative path under public/), as the originals did on delete. */
    protected function unlinkUpload(?string $rel): void
    {
        if ($rel && file_exists(public_path($rel))) {
            @unlink(public_path($rel));
        }
    }
}
