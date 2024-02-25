<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAffidavitRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.admin_email'))
            ->cc(explode(',', config('mail.admin_notice_email_cc')))
            ->subject('New Request Notification')
            ->markdown('emails.admin.AdminAffidavitRequestNotificationEmail');
    }
}
