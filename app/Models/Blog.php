<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `blogs` (original column names kept; timestamps are set explicitly by callers). */
class Blog extends Model
{
    protected $table = 'blogs';
    protected $guarded = [];
    public $timestamps = false;
}
