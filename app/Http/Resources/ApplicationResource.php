<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'application_id' =>$this->application_id,
            'university_name' =>$this->university_name,
            'course_level' =>$this->course_level,
            'course_intake' =>$this->course_intake,
            'course_name' =>$this->course_name,
            'student_name' =>$this->student_name,
            'student_email' =>$this->student_email,
            'student_number' =>$this->student_number,
            'student_dob' =>$this->student_dob,
            'visa_refusal' =>$this->visa_refusal,
            'passport_number' =>$this->passport_number,
            'passport_expire_date' =>$this->passport_expire_date,
            'nationality' =>$this->nationality,
            'user'=>$this->user()->first()->getName(),
            'message'=>$this->messages()->latest()->first(),
            'updated_at'=>$this->updated_at
        ];
    }
}
