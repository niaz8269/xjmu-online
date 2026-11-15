<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends ContentCrudController
{
    protected string $table = 'gallery';
    protected string $slug = 'manage-gallery';
    protected string $pageTitle = 'Gallery';
    protected string $pageSubtitle = 'Photos shown in the public gallery';
    protected string $view = 'admin.content.gallery';
    protected string $orderBy = 'sort_order, id DESC';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $img = Uploads::save($request, 'image', 'gallery', Uploads::imageExt(), Uploads::imageMime(), 6 * 1024 * 1024);
        if ($id === 0 && ! $img) {
            return 'Please choose an image to upload.';
        }
        $vals = ['title' => $this->str($request, 'title'), 'category' => $this->str($request, 'category'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($id > 0) {
            if ($img) {
                $vals['image_path'] = $img;
            }
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Image updated.';
        }
        DB::table($this->table)->insert($vals + ['image_path' => $img]);
        return 'Image added.';
    }

    protected function delete(Request $request): string
    {
        $id = (int) $request->post('id');
        $img = DB::table($this->table)->where('id', $id)->value('image_path');
        DB::table($this->table)->where('id', $id)->delete();
        $this->unlinkUpload($img);

        return 'Image deleted.';
    }
}
