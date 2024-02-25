<?php

namespace App\Events\Document;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestAdminAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $detail;

    public $session;

    public function __construct($details, $session)
    {
        $this->detail = $details;
        $this->session = $session;
    }
}
