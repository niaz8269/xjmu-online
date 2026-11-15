<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `site_settings` (original column names kept; timestamps are set explicitly by callers). */
class SiteSetting extends Model
{
    protected $table = 'site_settings';
    protected $guarded = [];
    public $timestamps = false;
}
