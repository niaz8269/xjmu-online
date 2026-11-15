<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminGateTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_non_admin_is_redirected_to_login(): void
    {
        $this->withSession(['user_id' => 5, 'role' => 'student', 'full_name' => 'S'])
            ->get('/admin')->assertRedirect('/login');
    }
}
