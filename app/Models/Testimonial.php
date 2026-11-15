<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `testimonials` (original column names kept; timestamps are set explicitly by callers). */
class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $guarded = [];
    public $timestamps = false;
}
