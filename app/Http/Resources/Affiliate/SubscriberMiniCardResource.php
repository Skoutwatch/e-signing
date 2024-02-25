<?php

namespace App\Http\Resources\Affiliate;

use App\Models\AffiliateSubscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AffiliateSubscriber
 */
class SubscriberMiniCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->loadMissing('user');

        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'photo' => config('externallinks.s3_storage_url').$this->user->image,
            'joined' => $this->joined_at->format('M jS, Y'),
            'joined_at' => $this->joined_at,
        ];
    }
}
