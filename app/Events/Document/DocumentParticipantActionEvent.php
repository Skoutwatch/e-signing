<?php

namespace App\Events\Document;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentParticipantActionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Document $document,
        public DocumentParticipant $participant,
        public DocumentParticipant $currentParticipant
    ) {
    }
}
