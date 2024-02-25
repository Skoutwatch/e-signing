<?php

namespace App\Http\Resources\Transaction;

use App\Models\Transaction;
use App\Services\Payment\PaymentGatewayService;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'subtotal' => $this->subtotal,
            'amount_paid' => $this->amount_paid,
            'next_billing_cycle_deduction' => $this->next_billing_cycle_deduction,
            'total' => $this->total,
            'payment_methods' => (new PaymentGatewayService(Transaction::find($this->id)))->allFinalGateWayChargeList(),
            'next_billing_cycle_date' => $this->next_billing_cycle_date,
            'discount_message' => $this->discount_message,
            'referral_code' => $this->referral_code,
            'discount_amount' => $this->discount_amount,
            'coupon_id' => $this->coupon_id,
            'upgrade_type' => $this->upgrade_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
