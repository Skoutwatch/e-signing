<?php

namespace App\Listeners;

use App\Events\Document\SignerSigned;
use App\Mail\DocumentSigned;
use Illuminate\Support\Facades\Mail;

class ParticipantSigned
{
    public function __construct()
    {
        //
    }

    public function handle(SignerSigned $event)
    {
        return Mail::send(new DocumentSigned($event->details));
    }
}
