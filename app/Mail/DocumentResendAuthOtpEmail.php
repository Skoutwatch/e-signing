<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentResendAuthOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $particpant;

    public function __construct($particpant)
    {
        $this->particpant = $particpant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $verify_status = ! empty($this->particpantdetail?->user?->user_access_code) ? '0' : '1';

        // $email = $this->particpantdetail?->user?->email;

        // $link = config('externallinks.verify_document_url')."?e=$email&f=$verify_status&document_id=".$this->particpantdetail?->document->id;

        // return $this->subject('Document Authentication')->markdown('emails.document.DocumentResendAuthOtpEmail')->with('link', $link);
        return $this->to($this->participant?->email)->subject('Document Authentication')->markdown('emails.document.DocumentResendAuthOtpEmail');
    }
}
