<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\User;
use App\Services\Document\DocumentConversionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadNotaryDocumentUploadFormJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $items;

    public $document;

    public $user;

    public function __construct($items, Document $document, User $user)
    {
        $this->items = $items;
        $this->document = $document;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $count = 1;
        foreach ($this->items['files'] as $file) {
            $count = $count + 1;
            (new DocumentConversionService())->storeRequestSingleUploadFiles($file, $this->document, $this->user, 'Awaiting', $count);
        }
    }
}
