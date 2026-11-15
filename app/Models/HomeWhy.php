<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `home_why` (original column names kept; timestamps are set explicitly by callers). */
class HomeWhy extends Model
{
    protected $table = 'home_why';
    protected $guarded = [];
    public $timestamps = false;
}
