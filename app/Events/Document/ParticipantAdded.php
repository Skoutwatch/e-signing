<?php

namespace App\Events\Document;

use App\Models\Document;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Document $document, public $message)
    {
        $this->document = $document;
        $this->message = $message;
    }
}
