<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `events` (original column names kept; timestamps are set explicitly by callers). */
class Event extends Model
{
    protected $table = 'events';
    protected $guarded = [];
    public $timestamps = false;
}
