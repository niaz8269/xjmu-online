<?php

namespace App\Http\Controllers\Admin;

use App\Support\Admin\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimonialController extends ContentCrudController
{
    protected string $table = 'testimonials';
    protected string $slug = 'manage-testimonials';
    protected string $pageTitle = 'Testimonials';
    protected string $pageSubtitle = 'Student & alumni quotes shown on the homepage';
    protected string $view = 'admin.content.testimonials';

    /** AJAX drag-and-drop reordering: save new sort order, respond JSON (no redirect, no try/catch like the original). */
    protected function beforeSave(Request $request)
    {
        if ($request->post('_do') !== 'reorder') {
            return null;
        }
        $ids = $request->post('ids', []);
        if (is_array($ids)) {
            $pos = 1;
            foreach ($ids as $rid) {
                DB::table($this->table)->where('id', (int) $rid)->update(['sort_order' => $pos * 10]);
                $pos++;
            }
        }

        return response()->json(['ok' => true]);
    }

    private function filter(Request $request): string
    {
        $f = $request->query('filter');

        return in_array($f, ['pending', 'published'], true) ? $f : 'all';
    }

    protected function viewData(Request $request, bool $isForm, ?array $edit): array
    {
        $counts = ['pending' => 0, 'published' => 0, 'total' => 0];
        if (! $isForm) {
            $counts = [
                'pending'   => DB::table($this->table)->where('is_published', 0)->count(),
                'published' => DB::table($this->table)->where('is_published', 1)->count(),
                'total'     => DB::table($this->table)->count(),
            ];
        }
        $scheme = $request->isSecure() ? 'https' : 'http';

        return [
            'counts'   => $counts,
            'filter'   => $this->filter($request),
            'shareUrl' => $scheme . '://' . ($request->server('HTTP_HOST') ?? 'xjmu.online') . '/submit-testimonial',
        ];
    }

    protected function listRows(Request $request): array
    {
        // Ordered by sort_order so the admin list mirrors the public display order.
        $q = DB::table($this->table);
        $f = $this->filter($request);
        if ($f === 'pending') {
            $q->where('is_published', 0);
        } elseif ($f === 'published') {
            $q->where('is_published', 1);
        }

        return $this->arrays($q->orderByRaw('sort_order, id')->get());
    }

    protected function persist(Request $request): string
    {
        $do = $request->post('_do');
        if ($do === 'approve') {
            DB::table($this->table)->where('id', (int) $request->post('id'))->update(['is_published' => 1]);
            return 'Testimonial approved — it is now live on the website.';
        }
        if ($do === 'hide') {
            DB::table($this->table)->where('id', (int) $request->post('id'))->update(['is_published' => 0]);
            return 'Testimonial hidden — no longer shown on the website.';
        }

        $id = $this->int($request, 'id');
        $name = $this->str($request, 'name');
        $quote = $this->str($request, 'quote');
        if ($name === '' || $quote === '') {
            return 'Name and quote are required.';
        }
        $photo = Uploads::save($request, 'photo', 'testimonials', Uploads::imageExt(), Uploads::imageMime(), 4 * 1024 * 1024);
        $vals = [
            'name' => $name, 'role' => $this->str($request, 'role'), 'location' => $this->str($request, 'location'),
            'quote' => $quote, 'category' => $request->post('category', 'student') === 'alumni' ? 'alumni' : 'student',
            'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request),
        ];
        if ($photo) {
            $vals['photo'] = $photo;
        }
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'Testimonial updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'Testimonial added.';
    }

    protected function delete(Request $request): string
    {
        $id = (int) $request->post('id');
        $photo = DB::table($this->table)->where('id', $id)->value('photo');
        DB::table($this->table)->where('id', $id)->delete();
        // Only remove uploaded photos, never the shared default avatar.
        if ($photo !== 'images/avatar.svg') {
            $this->unlinkUpload($photo);
        }

        return 'Testimonial deleted.';
    }
}
