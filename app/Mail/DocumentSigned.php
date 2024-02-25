<?php

namespace App\Mail;

use App\Models\DocumentParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentSigned extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $detail;

    public function __construct(DocumentParticipant $detail)
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
        $email = $this->detail?->document?->user?->email;

        $link = config('externallinks.verify_document_url')."?e=$email&document_id=".$this->detail?->document?->id;

        return $this->to($email)->subject('Your document has been signed!')->markdown('emails.document.DocumentSignedEmail')->with('link', $link);
    }
}
