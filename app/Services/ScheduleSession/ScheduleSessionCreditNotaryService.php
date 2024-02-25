<?php

namespace App\Services\ScheduleSession;

use App\Models\Transaction;
use App\Models\User;

class ScheduleSessionCreditNotaryService
{
    public function __construct(public User $user, public $model)
    {
    }

    public function creditWallet()
    {
        $details = [
            'title' => "Wallet Credit for {$this->model->title}",
            'details' => "Wallet Credit for {$this->model->title}",
            'amount' => 0,
        ];

        $this->creditTransaction($details);
    }

    public function creditTransaction($details)
    {
        Transaction::create([
            'title' => $details['title'],
            'details' => $details['details'],
            'actor_id' => $this->user->id,
            'transaction_type' => 'Credit',
            'actor_type' => class_basename($this->user),
            'user_id' => $this->user->id,
            'subtotal' => $details['amount'],
            'total' => $details['amount'],
            'platform_initiated' => 'Web',
            'transactionable_type' => class_basename($this->user),
            'transactionable_id' => $this->model->id,
            'payment_reference' => $this->model->id,
            'currency' => 'NGN',
            'success' => true,
            'status' => 'Paid',
            'payment_gateway' => 'Wallet',
            'payment_gateway_method' => 'Wallet',
            'payment_gateway_message' => 'Successful',
            'payment_gateway_json_response' => json_encode($this->model),
        ]);
    }
}
