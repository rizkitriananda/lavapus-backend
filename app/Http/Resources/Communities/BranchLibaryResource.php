<?php

namespace App\Http\Resources\Communities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchLibaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'maps_link' => $this->maps_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
