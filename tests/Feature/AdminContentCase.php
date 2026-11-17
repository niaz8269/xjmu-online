<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Shared helpers for the AdminContent*Test feature tests (in-memory sqlite, logged-in admin session). */
abstract class AdminContentCase extends TestCase
{
    use RefreshDatabase;

    /** @var string[] web-relative upload paths created by a test; removed in tearDown */
    protected array $created = [];

    protected function admin(): static
    {
        // The admin gate re-checks the role in the database on every request, so the user must really exist.
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Tester', 'email' => 'tester' . uniqid() . '@example.com', 'password' => 'x', 'role' => 'admin', 'is_verified' => 1,
        ]);

        return $this->withSession(['user_id' => $id, 'role' => 'admin', 'full_name' => 'Tester']);
    }

    protected function png(string $name = 'a.png'): UploadedFile
    {
        $bytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($name, $bytes);
    }

    protected function pdf(string $name = 'a.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\n");
    }

    protected function track(?string $rel): ?string
    {
        if ($rel) {
            $this->created[] = $rel;
        }

        return $rel;
    }

    protected function tearDown(): void
    {
        foreach ($this->created as $rel) {
            @unlink(public_path($rel));
        }
        parent::tearDown();
    }

    protected function row(string $table, int $id): ?array
    {
        $r = DB::table($table)->where('id', $id)->first();

        return $r ? (array) $r : null;
    }
}
