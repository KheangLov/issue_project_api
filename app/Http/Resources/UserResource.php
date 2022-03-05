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
        if (!request()->auth) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'profile' => $this->profile,
                'is_disabled' => $this->is_disabled,
                'access_token' => request()->session()->get('client_access_token'),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_disabled' => $this->is_disabled,
            'roles' => $this->roles->pluck(['name']),
            'profile' => $this->profile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
