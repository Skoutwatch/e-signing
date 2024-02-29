<?php

namespace App\Listeners\Partner;

use App\Mail\Partner\UserFirstTransactionMail;
use Illuminate\Support\Facades\Mail;

class UserFirstTransactionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        Mail::to($event->partner->email)->send(new UserFirstTransactionMail($event->transaction, $event->partner));
    }
}
