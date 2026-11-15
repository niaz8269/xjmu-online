<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `downloads` (original column names kept; timestamps are set explicitly by callers). */
class Download extends Model
{
    protected $table = 'downloads';
    protected $guarded = [];
    public $timestamps = false;
}
