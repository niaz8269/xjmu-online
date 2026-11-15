<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `education_records` (original column names kept; timestamps are set explicitly by callers). */
class EducationRecord extends Model
{
    protected $table = 'education_records';
    protected $guarded = [];
    public $timestamps = false;
}
