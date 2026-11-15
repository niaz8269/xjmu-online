<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `documents` (original column names kept; timestamps are set explicitly by callers). */
class Document extends Model
{
    protected $table = 'documents';
    protected $guarded = [];
    public $timestamps = false;
}
