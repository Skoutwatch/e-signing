<?php

namespace App\Events\Document;

use App\Models\Document;
use App\Models\DocumentUpload;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentShareEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Document $document,
        public DocumentUpload $completedDocument,
        public $email,
    ) {
    }
}
