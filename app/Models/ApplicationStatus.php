<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $fillable =[
        'application_submission',
        'conditional_offer_letter_ready',
        'unconditional_offer_letter_ready',
        'payment_done',
        'university_interview',
        'cas_interview',
        'enrollment',
        'visa'
    ];
}
