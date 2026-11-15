<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `home_steps` (original column names kept; timestamps are set explicitly by callers). */
class HomeStep extends Model
{
    protected $table = 'home_steps';
    protected $guarded = [];
    public $timestamps = false;
}
