<?php

namespace App\Http\Resources\Affiliate;

use App\Models\AffiliateEarning;
use App\Services\Affiliate\EarningService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AffiliateEarning */
class EarningResource extends JsonResource
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
            'user_name' => $this->user->name,
            'date' => $this->created_at->format('M j, Y - g:ia'),
            'payable' => EarningService::payableName($this->resource),
            'amount' => $this->amount,
            'amount_formatted' => number_format($this->amount, 2),
        ];
    }
}
