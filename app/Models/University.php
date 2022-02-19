<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'link',
        'tuitionfees',
    ];

    /**
     * Get all of the courses for the University
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
