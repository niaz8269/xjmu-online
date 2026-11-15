<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `applications` (original column names kept; timestamps are set explicitly by callers). */
class Application extends Model
{
    protected $table = 'applications';
    protected $guarded = [];
    public $timestamps = false;
}
