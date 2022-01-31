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
        'course_intake',
        'course_name',
        'student_name',
        'student_email',
        'student_number',
        'student_dob',
        'visa_refusal',
        'passport_number',
        'passport_expire_date',
        'nationality',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('academic');
        $this->addMediaCollection('cv');
        $this->addMediaCollection('recomendation');
        $this->addMediaCollection('refference');
        $this->addMediaCollection('english');
        $this->addMediaCollection('work');
        $this->addMediaCollection('passport');
        $this->addMediaCollection('visa');
        $this->addMediaCollection('sop');
        $this->addMediaCollection('conditional');
        $this->addMediaCollection('unconditional');
        $this->addMediaCollection('other');
    }

    /**
     * Get the user that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get all of the messages for the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    /**
     * The statuses that belong to the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function statuses()
    {
        return $this->belongsToMany(Status::class)
        ->withTimestamps()
        ->withPivot('status');
    }
}
