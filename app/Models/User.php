<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** Legacy `users` table (full_name, email, password, role, is_superadmin, is_verified, verification_token). */
class User extends Authenticatable
{
    protected $table = 'users';
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ['password', 'verification_token'];
}
