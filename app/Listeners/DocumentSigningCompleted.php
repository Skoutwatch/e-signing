<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

class DocumentSigningCompleted
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        return Mail::send(new \App\Mail\SigningCompleted($event->participant));
    }
}
