<?php

namespace App\Http\Resources\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'iso3' => $this->iso3,
            'iso2' => $this->iso2,
            'numeric_code' => $this->numeric_code,
            'phone_code' => $this->phone_code,
            'capital' => $this->capital,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'tld' => $this->tld,
            'native' => $this->native,
            'region' => $this->region,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'emoji' => $this->emoji,
            'emojiU' => $this->emojiU,
            'active' => $this->active,
            'states' => StateResource::collection($this->whenLoaded('states')),
        ];
    }
}
