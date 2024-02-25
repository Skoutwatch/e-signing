<?php

namespace App\Observers\DocumentResource;

use App\Models\DocumentResourceTool;
use App\Services\Document\DocumentAuditTrailService;

class DocumentResourceToolObserver
{
    public function created(DocumentResourceTool $documentResourceTool)
    {
        (new DocumentAuditTrailService(auth()->user, $documentResourceTool->document))->annotationAdded($documentResourceTool);
    }

    public function updated(DocumentResourceTool $documentResourceTool)
    {
        $documentResourceTool->append_print_id != null && $documentResourceTool->isDirty('append_print_id')
            ?? (new DocumentAuditTrailService(auth()->user, $documentResourceTool->document))->documentAnnotationSigned($documentResourceTool);
    }

    public function deleted(DocumentResourceTool $documentResourceTool)
    {
        //
    }

    public function restored(DocumentResourceTool $documentResourceTool)
    {
        //
    }

    public function forceDeleted(DocumentResourceTool $documentResourceTool)
    {
        //
    }
}
