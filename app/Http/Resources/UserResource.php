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
                'roles' => $this->roles->pluck('name'),
                'created_at' => $this->created_at ?? 'N/A',
                'updated_at' => $this->updated_at ?? 'N/A',
                'deleted_at' => $this->deleted_at ?? 'N/A',
                'created_by' => $this->CreatedByFullName ?? 'N/A',
                'updated_by' => $this->UpdatedByFullName ?? 'N/A',
                'deleted_by' => $this->DeletedByFullName ?? 'N/A',
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
