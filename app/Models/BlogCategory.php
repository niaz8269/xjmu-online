<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `blog_categories` (original column names kept; timestamps are set explicitly by callers). */
class BlogCategory extends Model
{
    protected $table = 'blog_categories';
    protected $guarded = [];
    public $timestamps = false;
}
