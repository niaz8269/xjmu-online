<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `consultants` (original column names kept; timestamps are set explicitly by callers). */
class Consultant extends Model
{
    protected $table = 'consultants';
    protected $guarded = [];
    public $timestamps = false;
}
