<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `page_sections` (original column names kept; timestamps are set explicitly by callers). */
class PageSection extends Model
{
    protected $table = 'page_sections';
    protected $guarded = [];
    public $timestamps = false;
}
