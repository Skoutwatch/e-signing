<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CardCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => CardResource::collection($this->collection),
        ];
    }
}
