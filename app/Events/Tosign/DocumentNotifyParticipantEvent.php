<?php

namespace App\Events\Tosign;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentNotifyParticipantEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Document $document, public DocumentParticipant $participant)
    {
        $this->document = $document;
        $this->participant = $participant;
    }
}
