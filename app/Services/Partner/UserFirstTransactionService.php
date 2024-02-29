<?php

namespace App\Services\Partner;

use App\Events\Partner\UserFirstTransactionEvent;
use App\Models\User;

class UserFirstTransactionService
{
    public function checkIfUserFirstTransaction(User $user)
    {
        if ($user->paidTransactions->count() <= 0) {

            $transaction = $user->transactions()->orderBy('created_at')->first();

            $transaction->partner ? event(new UserFirstTransactionEvent($transaction, $transaction->partner)) : null;
        }
    }
}
