<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
   /**
     * @var
     */
    private $token;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $token)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        
        $this->token = $token;
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'phone' => $this->phone,
            'roles' => $this->roles,
            'status' => $this->status,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'token' => $this->token
        ];
    }
    // public static $wrap = 'user';
}
