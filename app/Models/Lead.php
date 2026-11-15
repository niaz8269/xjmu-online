<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `leads` (original column names kept; timestamps are set explicitly by callers). */
class Lead extends Model
{
    protected $table = 'leads';
    protected $guarded = [];
    public $timestamps = false;
}
