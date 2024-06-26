<?php

namespace App\Http\Resources\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'state_code' => $this->state_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'type' => $this->type,
        ];
    }
}
