<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `scholarships` (original column names kept; timestamps are set explicitly by callers). */
class Scholarship extends Model
{
    protected $table = 'scholarships';
    protected $guarded = [];
    public $timestamps = false;
}
