<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Maps legacy table `gallery` (original column names kept; timestamps are set explicitly by callers). */
class GalleryItem extends Model
{
    protected $table = 'gallery';
    protected $guarded = [];
    public $timestamps = false;
}
