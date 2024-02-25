<?php

namespace App\Observers\DocumentUpload;

use App\Events\Document\DocumentRequestAdminAction;
use App\Models\Document;
use App\Models\DocumentUpload;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class DocumentUploadObserver
{
    public function created(DocumentUpload $documentUpload)
    {
        Nova::whenServing(function (NovaRequest $request) use ($documentUpload) {
            $document = Document::find($documentUpload->document_id);
            $document->status = 'Processed';
            $document->save();

            $document->scheduleSession->update(['status' => $request->status]);

            if ($request->status == 'Completed') {
                event(new DocumentRequestAdminAction($document, $session = []));
            }
        });
    }

    public function updated(DocumentUpload $documentUpload)
    {
        Nova::whenServing(function (NovaRequest $request) use ($documentUpload) {
            $document = Document::find($documentUpload->document_id);
            $document->status = 'Processed';
            $document->save();

            $document->scheduleSession->update(['status' => $request->status]);

            if ($request->status == 'Completed' || $request->status == 'In-view') {
                event(new DocumentRequestAdminAction($document, $session = []));
            }
        });
    }

    // public function retrieved(DocumentUpload $documentUpload)
    // {
    //     Nova::whenServing(function (NovaRequest $request) use ($documentUpload) {
    //         if ($documentUpload->status != 'Completed') {

    //             $document = Document::find($documentUpload->document_id);
    //             $document->status = 'Seen';
    //             $document->save();

    //             $document->scheduleSession->update(['status' => 'Seen']);
    //         }
    //     });
    // }

    // public function uploadToS3()
    // {

    // }
}
