<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ic' => $this->ic,
            'address' => $this->address,
            'username' => $this->username,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => $this->role,
        ];
        //return parent::toArray($request);
    }
}
