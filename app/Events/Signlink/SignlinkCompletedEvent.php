<?php

namespace App\Events\Signlink;

use App\Models\Document;
use App\Models\SignlinkDocumentUserFormData;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignlinkCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SignlinkDocumentUserFormData $userFormData,
        public Document $document,
        public Document $parentDocument
    ) {
    }
}
