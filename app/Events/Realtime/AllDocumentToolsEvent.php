<?php

namespace App\Events\Realtime;

use App\Models\Document;
use App\Models\DocumentResourceTool;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllDocumentToolsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tools;

    public function __construct(private Document $document)
    {
        $toolsViaDocuments = $this->document?->uploads
                    ? $this->document?->uploads->pluck('id')->toArray() : null;

        $allTools = DocumentResourceTool::with('appendPrint')->whereIn('document_upload_id', $toolsViaDocuments)->orderBy('created_at', 'DESC')->get();

        $data = ['item' => base64_encode($allTools)];

        $this->tools = ($data);
    }

    public function broadcastAs()
    {
        return 'AllDocumentTools';
    }

    public function broadcastOn()
    {
        return new Channel('all-document-tools.'.$this->document->id);
    }
}
