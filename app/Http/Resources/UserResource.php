<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    // public static $wrap = 'user';

    public function toArray($request)
    {
        return [
            'id' => $this->mem_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->username,
            'password' => $this->password,
            'address' => $this->address,
            'mem_active' => $this->mem_active,
            'role' => $this->role
        ];
    }
}
