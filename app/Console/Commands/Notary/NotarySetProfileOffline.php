<?php

namespace App\Console\Commands\Notary;

use App\Models\User;
use Illuminate\Console\Command;

class NotarySetProfileOffline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notary:offline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all notaries offline';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notaries = User::where('role', 'Notary')->get();

        foreach ($notaries as $notary) {
            $notary->is_online = '';
        }

        return Command::SUCCESS;
    }
}
