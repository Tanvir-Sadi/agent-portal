<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Course extends Pivot
{
    protected $fillable = [
        'name',
        'level',
        'link',
        'university_id'
    ];
}
