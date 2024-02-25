<?php

namespace App\Http\Resources\Affiliate;

use App\Models\AffiliateSubscriber;
use App\Services\Affiliate\SubscriberService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AffiliateSubscriber */
class SubscriberResource extends JsonResource
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
            'name' => $this->user->name,
            'photo' => config('externallinks.s3_storage_url').$this->user->image,
            'date_joined' => $this->joined_at->format('M j, Y - g:ia'),
            'plan_name' => SubscriberService::planName($this->resource),
            'status' => $this->status,
            'status_string' => $this->status_string,
            'commission' => $this->commission, 2,
            'commission_formatted' => number_format($this->commission, 2),
        ];
    }
}
