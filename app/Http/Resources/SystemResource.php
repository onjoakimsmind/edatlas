<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemResource extends JsonResource
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
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z,
            'population' => $this->population,
            'distance' => $this->distance,
            'economy' => $this->economy,
            'second_economy' => $this->second_economy,
            'allegiance' => $this->allegiance,
            'government' => $this->government,
            'security' => $this->security,
            'faction' => $this->whenLoaded('faction'),
            'factions' => $this->whenLoaded('factions'),
            'conflicts' => $this->whenLoaded('conflicts'),
            'updated_at' => $this->updated_at,
        ];
    }
}
