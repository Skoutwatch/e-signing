<?php

namespace App\Services\Transaction;

use App\Models\User;
use App\Models\Transaction;
use App\Events\Partner\UserFirstTransactionEvent;

class TransactionService
{
    public function firstTransaction(Transaction $transaction, User $user)
    {
        if ($user->paidTransactions->count() === 1) {

            $transaction->update(['is_first_transaction' => true]);

            $transaction = $user->transactions()->orderBy('created_at')->first();

            $transaction->partner ? event(new UserFirstTransactionEvent($transaction, $transaction->partner)) : null;
        }
    }
}
