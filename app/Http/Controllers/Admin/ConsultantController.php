<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultantController extends ContentCrudController
{
    protected string $table = 'consultants';
    protected string $slug = 'manage-consultants';
    protected string $pageTitle = 'Consultants';
    protected string $pageSubtitle = 'Counsellors shown on the public site';
    protected string $view = 'admin.content.consultants';
    protected string $orderBy = 'sort_order, id DESC';

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $name = $this->str($request, 'name');
        if ($name === '') {
            return 'Name is required.';
        }
        $photo = Uploads::save($request, 'photo', 'consultants', Uploads::imageExt(), Uploads::imageMime(), 4 * 1024 * 1024);
        $qr = Uploads::save($request, 'qr_image', 'consultants', Uploads::imageExt(), Uploads::imageMime(), 4 * 1024 * 1024);
        // Base columns always written; uploads only when provided.
        $vals = [
            'name' => $name, 'role' => $this->str($request, 'role'), 'whatsapp' => $this->str($request, 'whatsapp'),
            'phone' => $this->str($request, 'phone'), 'email' => $this->str($request, 'email'), 'wechat' => $this->str($request, 'wechat'),
            'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request),
        ];
        if ($photo) {
            $vals['photo'] = $photo;
        }
        if ($qr) {
            $vals['qr_image'] = $qr;
        }
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Consultant updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Consultant added.';
    }

    protected function delete(Request $request): string
    {
        $id = (int) $request->post('id');
        $photo = DB::table($this->table)->where('id', $id)->value('photo');
        DB::table($this->table)->where('id', $id)->delete();
        $this->unlinkUpload($photo);

        return 'Consultant deleted.';
    }
}
