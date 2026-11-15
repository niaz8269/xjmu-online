<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `home_facts` (original column names kept; timestamps are set explicitly by callers). */
class HomeFact extends Model
{
    protected $table = 'home_facts';
    protected $guarded = [];
    public $timestamps = false;
}
