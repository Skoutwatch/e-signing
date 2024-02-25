<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;

class CouponService
{
    public function findTotalDiscountByAmount(float $total, User $user)
    {
        $couponDetails = $this->checkCoupon($user->referral_code);

        $discount = $this->checkUserHasADiscountViaCoupon($total, $user);

        return [
            'discount_message' => $discount > 0 ? 'Available' : 'Not Available',
            'referral_code' => $couponDetails?->referral_code,
            'discount_amount' => $discount > 0 ? $discount : 0,
            'coupon_id' => $couponDetails?->id,
        ];
    }

    public function checkSubscriptionTransactionDiscount(Transaction $transaction, User $user)
    {
        $couponDetails = $this->checkCoupon($user->referral_code);

        $discount = (int) $this->checkUserHasADiscountViaCoupon($transaction->subtotal, $user);

        $plan = $transaction->transactionable_type == 'Plan' ? Plan::find($transaction->transactionable_id) : null;

        $activateDiscount = $plan->name == 'Business' && $discount > 0 ? true : false;

        $transaction->update([
            'discount_message' => $activateDiscount ? 'Available' : 'Not Available',
            'referral_code' => $couponDetails?->referral_code,
            'discount_amount' => $activateDiscount ? $discount : 0,
            'coupon_id' => $couponDetails?->id,
            'total' => $activateDiscount ? ($transaction->total - $discount) : $transaction->total,
        ]);

        return $transaction;
    }

    public function checkUserHasADiscountViaCoupon($amount, $user): int
    {
        $couponDetails = $this->checkCoupon($user->referral_code);

        if ($couponDetails == null) {
            return 0;
        }

        // if($couponDetails->amount != null){
        //     $couponAmount = $couponDetails->amount;
        // }

        if ($couponDetails->percentage != null) {
            $couponConvertion = $couponDetails->percentage / 100;
            $couponAmount = $amount * $couponConvertion;
        }

        return $couponAmount;
    }

    public function checkCoupon($referral_code)
    {
        return Coupon::where('referral_code', $referral_code)->first();
    }
}
