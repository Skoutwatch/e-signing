<?php

namespace App\Observers\Session;

use App\Events\Document\DocumentRequestAdminAction;
use App\Models\Document;
use App\Models\DocumentUpload;
use App\Models\ScheduleSession;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class ScheduleSessionObserver
{
    public function updated(ScheduleSession $ScheduleSession)
    {
        Nova::whenServing(function (NovaRequest $request) use ($ScheduleSession) {
            $documentupload = DocumentUpload::WHERE(['document_id' => $ScheduleSession->schedule_id])->first();
            $documentupload->status = $request->status;
            $documentupload->save();

            $document = Document::where(['id' => $ScheduleSession->schedule_id])->first();
            $document->status = $request->status;
            $document->save();

            event(new DocumentRequestAdminAction($document, $ScheduleSession));
        });
    }
}
