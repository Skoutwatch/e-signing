<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanPaymentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Transaction $detail, public Plan $plan)
    {
        $this->detail = $detail;
        $this->plan = $plan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->detail?->user?->email ? $this->detail?->user?->email : auth('api')->user()->email)->subject('Payment successful')->markdown('emails.subscriptions.PlanPaymentConfirmation');
    }
}
