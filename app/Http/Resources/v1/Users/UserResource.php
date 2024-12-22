<?php

namespace App\Http\Resources\v1\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        =>$this->id,
            'name'      =>$this->name,
            'username'  =>$this->username,
            'avatar'    =>storageUrl($this->avatar),
            'type'      =>$this->type->toString(),
            'is_active' =>(bool)$this->is_active,
        ];
    }
}
