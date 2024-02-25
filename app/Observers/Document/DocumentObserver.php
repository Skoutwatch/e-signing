<?php

namespace App\Observers\Document;

use App\Models\Document;

class DocumentObserver
{
    public function created(Document $document)
    {
    }

    public function updated(Document $document)
    {
        //
    }

    public function deleted(Document $document)
    {
        //
    }

    public function restored(Document $document)
    {
        //
    }

    public function forceDeleted(Document $document)
    {
        //
    }
}
