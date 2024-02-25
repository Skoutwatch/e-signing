<?php

namespace App\Console\Commands\Transaction;

use App\Models\Transaction;
use App\Services\Payment\PaymentProcessingService;
use Illuminate\Console\Command;

class ProcessNextBillingCycleTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initiate:next-user-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will process the next billing cycle transaction yet to be given subscription';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where('process_for_next_billing_cycle', true)
            ->where('next_billing_cycle_date_processed', false)
            ->get();

        foreach ($transactions as $transaction) {
            (new PaymentProcessingService())->processAfterSuccess($transaction);

            $transaction->update([
                'process_for_next_billing_cycle' => false,
                'next_billing_cycle_date_processed' => true,
            ]);
        }

        return Command::SUCCESS;
    }
}
