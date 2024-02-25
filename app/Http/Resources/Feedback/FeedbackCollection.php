<?php

namespace App\Http\Resources\Feedback;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FeedbackCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
