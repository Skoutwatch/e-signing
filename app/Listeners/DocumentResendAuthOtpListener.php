<?php

namespace App\Listeners;

use App\Events\Document\DocumentResendAuthOtpEvent;
use App\Mail\DocumentResendAuthOtpEmail;
use Illuminate\Support\Facades\Mail;

class DocumentResendAuthOtpListener
{
    public function __construct()
    {
    }

    public function handle(DocumentResendAuthOtpEvent $event)
    {
        return Mail::send(new DocumentResendAuthOtpEmail($event->participant));
    }
}
