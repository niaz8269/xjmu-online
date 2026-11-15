<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;

/** CRUD screens with image/file uploads: consultants, gallery, downloads. */
class AdminContentUploadsTest extends AdminContentCase
{
    public function test_consultants_with_photo_and_qr(): void
    {
        $url = '/admin/manage-consultants';
        $this->admin()->post($url, ['name' => ''])->assertRedirect($url);
        $this->assertSame(0, DB::table('consultants')->count());

        $this->admin()->post($url, ['id' => 0, 'name' => 'Ann', 'role' => 'Adm', 'whatsapp' => '123', 'is_published' => 'on', 'sort_order' => '3',
            'photo' => $this->png('p.png'), 'qr_image' => $this->png('q.png')])->assertRedirect($url);
        $row = (array) DB::table('consultants')->first();
        $this->track($row['photo']);
        $this->track($row['qr_image']);
        $this->assertStringStartsWith('uploads/consultants/consultants_', $row['photo']);
        $this->assertFileExists(public_path($row['photo']));
        $this->admin()->get($url)->assertSee('Consultant added.', false)->assertSee('../' . $row['photo'], false);
        $this->admin()->get($url . '?action=edit&id=' . $row['id'])->assertSee('(leave empty to keep current)', false);

        // update without files keeps them; bad extension reports the original message and changes nothing
        $this->admin()->post($url, ['id' => $row['id'], 'name' => 'Ann B', 'is_published' => 'on'])->assertRedirect($url);
        $this->assertSame($row['photo'], $this->row('consultants', $row['id'])['photo']);
        $this->assertSame('Ann B', $this->row('consultants', $row['id'])['name']);
        $this->admin()->post($url, ['id' => $row['id'], 'name' => 'Zed', 'photo' => $this->pdf('x.pdf')])->assertRedirect($url);
        $this->assertSame('Ann B', $this->row('consultants', $row['id'])['name']);
        $this->admin()->get($url)->assertSee('Error: File type not allowed.', false);

        // delete removes the photo file (as the original) and the row
        $this->admin()->post($url, ['_do' => 'delete', 'id' => $row['id']])->assertRedirect($url);
        $this->assertSame(0, DB::table('consultants')->count());
        $this->assertFileDoesNotExist(public_path($row['photo']));
        $this->admin()->get($url)->assertSee('Consultant deleted.', false);
    }

    public function test_gallery_requires_image_on_create_and_replaces_on_update(): void
    {
        $url = '/admin/manage-gallery';
        $this->admin()->post($url, ['id' => 0, 'title' => 'T'])->assertRedirect($url);
        $this->assertSame(0, DB::table('gallery')->count());
        $this->admin()->get($url)->assertSee('Please choose an image to upload.', false);

        $this->admin()->post($url, ['id' => 0, 'title' => 'T', 'category' => 'Campus', 'sort_order' => '1', 'is_published' => 'on', 'image' => $this->png()])->assertRedirect($url);
        $row = (array) DB::table('gallery')->first();
        $this->track($row['image_path']);
        $this->assertFileExists(public_path($row['image_path']));
        $this->admin()->get($url)->assertSee('Image added.', false)->assertSee('alt="T"', false);

        $this->admin()->post($url, ['id' => $row['id'], 'title' => 'T2', 'image' => $this->png('b.png'), 'is_published' => 'on'])->assertRedirect($url);
        $upd = $this->row('gallery', $row['id']);
        $this->track($upd['image_path']);
        $this->assertNotSame($row['image_path'], $upd['image_path']);
        $this->assertSame('T2', $upd['title']);

        $this->admin()->post($url, ['_do' => 'delete', 'id' => $row['id']]);
        $this->assertSame(0, DB::table('gallery')->count());
        $this->assertFileDoesNotExist(public_path($upd['image_path']));
        $this->admin()->get($url)->assertSee('Image deleted.', false)->assertSee('No images yet.', false);
    }

    public function test_downloads_pdf_upload_size_and_delete(): void
    {
        $url = '/admin/manage-downloads';
        $this->admin()->post($url, ['id' => 0, 'title' => ''])->assertRedirect($url);
        $this->admin()->get($url)->assertSee('Title is required.', false);
        $this->admin()->post($url, ['id' => 0, 'title' => 'Form'])->assertRedirect($url);
        $this->admin()->get($url)->assertSee('Please choose a file to upload.', false);
        $this->assertSame(0, DB::table('downloads')->count());

        $this->admin()->post($url, ['id' => 0, 'title' => 'Form', 'category' => 'Forms', 'file' => $this->pdf(), 'is_published' => 'on'])->assertRedirect($url);
        $row = (array) DB::table('downloads')->first();
        $this->track($row['file_path']);
        $this->assertStringStartsWith('uploads/documents/documents_', $row['file_path']);
        $this->assertSame(filesize(public_path($row['file_path'])), (int) $row['file_size']);
        $this->admin()->get($url)->assertSee('Download added.', false);

        $this->admin()->post($url, ['id' => $row['id'], 'title' => 'Form 2', 'description' => 'd'])->assertRedirect($url);
        $upd = $this->row('downloads', $row['id']);
        $this->assertSame('Form 2', $upd['title']);
        $this->assertSame($row['file_path'], $upd['file_path']);
        $this->assertSame(0, (int) $upd['is_published']);

        $this->admin()->post($url, ['_do' => 'delete', 'id' => $row['id']]);
        $this->assertFileDoesNotExist(public_path($row['file_path']));
        $this->assertSame(0, DB::table('downloads')->count());
    }

    public function test_guests_are_redirected(): void
    {
        foreach (['manage-consultants', 'manage-gallery', 'manage-downloads'] as $s) {
            $this->get('/admin/' . $s)->assertRedirect('/login');
            $this->post('/admin/' . $s, ['name' => 'x'])->assertRedirect('/login');
        }
    }
}
