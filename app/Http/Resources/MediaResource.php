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
            'sop' => MediaTypeResource::collection($this->getMedia('sop')),
            'cv' => MediaTypeResource::collection($this->getMedia('cv')),
        ];
    }
}
