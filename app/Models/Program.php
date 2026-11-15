<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `programs` (original column names kept; timestamps are set explicitly by callers). */
class Program extends Model
{
    protected $table = 'programs';
    protected $guarded = [];
    public $timestamps = false;
}
