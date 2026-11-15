<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    private function mk(string $email, string $role = 'user', int $super = 0): int
    {
        return DB::table('users')->insertGetId([
            'full_name' => 'N ' . $email, 'email' => $email, 'password' => 'x', 'role' => $role, 'is_superadmin' => $super, 'is_verified' => 1,
        ]);
    }

    private function sess(int $id): array
    {
        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss'];
    }

    public function test_users_list_search_and_superadmin_link(): void
    {
        $super = $this->mk('super@example.com', 'admin', 1);
        $plain = $this->mk('plain@example.com', 'admin', 0);
        $this->mk('alice@example.com');
        $r = $this->withSession($this->sess($super))->get('/admin/users?search=alice');
        $r->assertOk()->assertSee('alice@example.com')->assertDontSee('plain@example.com')->assertSee('Manage Users (promote / demote)')
            ->assertSee('Showing 1 of 1 users');
        $this->withSession($this->sess($plain))->get('/admin/users')->assertOk()->assertDontSee('Manage Users (promote');
    }

    public function test_non_superadmin_is_refused(): void
    {
        $admin = $this->mk('a@example.com', 'admin', 0);
        $u = $this->mk('u@example.com');
        $s = $this->sess($admin);
        $this->withSession($s)->get('/admin/manage_users')->assertOk()->assertSee('Access denied. Superadmin only.');
        $this->withSession($s)->post('/admin/promote_user', ['user_id' => $u])->assertOk()->assertSee('Access Denied');
        $this->withSession($s)->post('/admin/demote_user', ['user_id' => $admin])->assertSee('Access Denied');
        $this->withSession($s)->post('/admin/delete_user', ['user_id' => $u])->assertSee('Access Denied');
        $this->assertSame('user', DB::table('users')->where('id', $u)->value('role'));
        $this->assertSame(1, DB::table('users')->where('id', $u)->count());
    }

    public function test_superadmin_can_promote_demote_and_delete(): void
    {
        $super = $this->mk('super@example.com', 'admin', 1);
        $u = $this->mk('u@example.com');
        $s = $this->sess($super);

        $this->withSession($s)->get('/admin/manage_users')->assertOk()->assertSee('action="promote_user"', false)->assertSee('name="_token"', false);

        // GET never changes anything
        $this->withSession($s)->get('/admin/promote_user?user_id=' . $u)->assertRedirect('/admin/manage_users');
        $this->assertSame('user', DB::table('users')->where('id', $u)->value('role'));

        $this->withSession($s)->post('/admin/promote_user', ['user_id' => $u])->assertRedirect('/admin/manage_users');
        $this->assertSame('admin', DB::table('users')->where('id', $u)->value('role'));
        $this->withSession($s)->get('/admin/manage_users')->assertSee('action="demote_user"', false);

        $this->withSession($s)->post('/admin/demote_user', ['user_id' => $u])->assertRedirect('/admin/manage_users');
        $this->assertSame('user', DB::table('users')->where('id', $u)->value('role'));

        $this->withSession($s)->post('/admin/delete_user', ['user_id' => $u])->assertRedirect('/admin/manage_users');
        $this->assertSame(0, DB::table('users')->where('id', $u)->count());
    }

    public function test_superadmins_cannot_be_deleted_not_even_by_themselves(): void
    {
        $super = $this->mk('super@example.com', 'admin', 1);
        $other = $this->mk('super2@example.com', 'admin', 1);
        $s = $this->sess($super);
        $this->withSession($s)->post('/admin/delete_user', ['user_id' => $super])->assertRedirect('/admin/manage_users');
        $this->withSession($s)->post('/admin/delete_user', ['user_id' => $other])->assertRedirect('/admin/manage_users');
        $this->assertSame(2, DB::table('users')->count());
        // missing / unknown ids are harmless
        $this->withSession($s)->post('/admin/delete_user', [])->assertRedirect('/admin/manage_users');
        $this->withSession($s)->post('/admin/delete_user', ['user_id' => 987654])->assertRedirect('/admin/manage_users');
        $this->assertSame(2, DB::table('users')->count());
    }
}
