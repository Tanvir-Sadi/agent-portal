<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'application_id',
        'university_name',
        'course_level',
        'course_name',
        'student_name',
        'student_email',
        'student_number',
        'student_dob',
        'visa_refusal',
        'nationality',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cv');
        $this->addMediaCollection('sop');
    }
}
