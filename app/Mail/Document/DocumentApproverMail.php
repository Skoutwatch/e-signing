<?php

namespace App\Mail\Document;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentApproverMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Document $document)
    {
    }

    public function build()
    {
        return $this->subject('Please Approve')->markdown('emails.DocumentApproverMail', [
            'document' => config('externallinks.s3_storage_url').$this->document?->file_url,
        ]);
    }
}
