<?php

namespace App\Console\Commands\Partner;

use App\Models\User;
use App\Services\Partner\UserFirstTransactionService;
use Illuminate\Console\Command;

class CheckFirstTransactionMonthlyEmail extends Command
{
    protected $signature = 'check:first-transaction-monthly';

    protected $description = 'Check for the first transaction of each user at the end of each month';

    public function handle(UserFirstTransactionService $userFirstTransactionService)
    {
        $users = User::where('is_partner', true)->get();

        foreach ($users as $user) {
            $userFirstTransactionService->checkIfUserFirstTransaction($user);
        }
    }
}
