<?php

namespace App\Listeners\Affiliate;

use App\Events\Affiliate\AffiliateRegisteredEvent;
use App\Mail\Affiliate\AffiliateRegisteredMail;
use App\Traits\Api\OtpTraits;

class AffiliateRegisteredListener
{
    use OtpTraits;

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
    public function handle(AffiliateRegisteredEvent $event): void
    {
        $otp = $this->generate_otp($event->affiliate->user->email);

        \Mail::to($event->affiliate->user->email)->send(new AffiliateRegisteredMail($event->affiliate, $otp, $event->new));
    }
}
