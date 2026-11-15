<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `home_programs` (original column names kept; timestamps are set explicitly by callers). */
class HomeProgram extends Model
{
    protected $table = 'home_programs';
    protected $guarded = [];
    public $timestamps = false;
}
