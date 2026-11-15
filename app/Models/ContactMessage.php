<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `contact_messages` (original column names kept; timestamps are set explicitly by callers). */
class ContactMessage extends Model
{
    protected $table = 'contact_messages';
    protected $guarded = [];
    public $timestamps = false;
}
