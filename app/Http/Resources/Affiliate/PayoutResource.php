<?php

namespace App\Http\Resources\Affiliate;

use App\Models\AffiliatePayout;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AffiliatePayout */
class PayoutResource extends JsonResource
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
            'account_name' => $this->bankDetail->bank_account_name,
            'account_number' => $this->bankDetail->bank_account_name,
            'amount' => $this->amount,
            'amount_formatted' => number_format($this->amount, 2),
            'date' => $this->created_at->format('M j, Y - g:ia'),
            'status' => $this->status,
            'status_string' => $this->status_string,
        ];
    }
}
