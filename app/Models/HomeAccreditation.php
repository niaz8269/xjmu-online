<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `home_accreditations` (original column names kept; timestamps are set explicitly by callers). */
class HomeAccreditation extends Model
{
    protected $table = 'home_accreditations';
    protected $guarded = [];
    public $timestamps = false;
}
