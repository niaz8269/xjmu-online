<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `mbbs_curriculum` (original column names kept; timestamps are set explicitly by callers). */
class Curriculum extends Model
{
    protected $table = 'mbbs_curriculum';
    protected $guarded = [];
    public $timestamps = false;
}
