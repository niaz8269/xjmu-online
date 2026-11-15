<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `personal_info` (original column names kept; timestamps are set explicitly by callers). */
class PersonalInfo extends Model
{
    protected $table = 'personal_info';
    protected $guarded = [];
    public $timestamps = false;
}
