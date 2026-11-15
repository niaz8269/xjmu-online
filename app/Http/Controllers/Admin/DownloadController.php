<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends ContentCrudController
{
    protected string $table = 'downloads';
    protected string $slug = 'manage-downloads';
    protected string $pageTitle = 'Downloads';
    protected string $pageSubtitle = 'Downloadable documents (PDF/DOC) shown on the public site';
    protected string $view = 'admin.content.downloads';
    protected string $orderBy = 'sort_order, id DESC';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $title = $this->str($request, 'title');
        $filePath = Uploads::save($request, 'file', 'documents', Uploads::docExt(), Uploads::docMime(), 20 * 1024 * 1024);
        if ($title === '') {
            return 'Title is required.';
        }
        if ($id === 0 && ! $filePath) {
            return 'Please choose a file to upload.';
        }
        $size = $filePath ? @filesize(public_path($filePath)) : null;
        $vals = ['title' => $title, 'description' => $this->str($request, 'description'), 'category' => $this->str($request, 'category'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            if ($filePath) {
                $vals += ['file_path' => $filePath, 'file_size' => $size];
            }
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Download updated.';
        }
        DB::table($this->table)->insert($vals + ['file_path' => $filePath, 'file_size' => $size]);
        return 'Download added.';
    }

    protected function delete(Request $request): string
    {
        $id = (int) $request->post('id');
        $fp = DB::table($this->table)->where('id', $id)->value('file_path');
        DB::table($this->table)->where('id', $id)->delete();
        $this->unlinkUpload($fp);

        return 'Download deleted.';
    }
}
