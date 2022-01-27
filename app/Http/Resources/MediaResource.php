<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Application; 

class MediaResource extends JsonResource
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
            'academic' => MediaTypeResource::collection($this->getMedia('academic')),
            'cv' => MediaTypeResource::collection($this->getMedia('cv')),
            'recomendation' => MediaTypeResource::collection($this->getMedia('recomendation')),
            'refference' => MediaTypeResource::collection($this->getMedia('refference')),
            'english' => MediaTypeResource::collection($this->getMedia('english')),
            'work' => MediaTypeResource::collection($this->getMedia('work')),
            'passport' => MediaTypeResource::collection($this->getMedia('passport')),
            'visa' => MediaTypeResource::collection($this->getMedia('visa')),
            'sop' => MediaTypeResource::collection($this->getMedia('sop')),
            'conditional' => MediaTypeResource::collection($this->getMedia('conditional')),
            'unconditional' => MediaTypeResource::collection($this->getMedia('unconditional')),
            'other' => MediaTypeResource::collection($this->getMedia('other')),
        ];
    }
}
