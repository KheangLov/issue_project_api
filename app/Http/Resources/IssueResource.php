<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
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
            'issue_type' => $this->issue_type,
            'status' => $this->status,
            'api_type' => $this->api_type,
            'resolution' => $this->resolution,
            'issued_at' => $this->issued_at ?? 'N/A',
            'resolved_at' => $this->resolved_at ?? 'N/A',
            'merchant' => $this->merchant,
            'description' => $this->description,
            'created_at' => $this->created_at ?? 'N/A',
            'updated_at' => $this->updated_at ?? 'N/A',
            'deleted_at' => $this->deleted_at ?? 'N/A',
            'created_by' => $this->CreatedByFullName ?? 'N/A',
            'updated_by' => $this->UpdatedByFullName ?? 'N/A',
            'deleted_by' => $this->DeletedByFullName ?? 'N/A',
        ];
    }
}
