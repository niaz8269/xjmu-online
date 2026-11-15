<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `faqs` (original column names kept; timestamps are set explicitly by callers). */
class Faq extends Model
{
    protected $table = 'faqs';
    protected $guarded = [];
    public $timestamps = false;
}
