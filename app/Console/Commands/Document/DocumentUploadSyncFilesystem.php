<?php

namespace App\Console\Commands\Document;

use App\Models\Document;
use App\Services\Document\DocumentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DocumentUploadSyncFilesystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:filesystem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all processing files to filesystem';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $documents = Document::latest()->get();

        $processed = 0;

        foreach ($documents as $document) {
            (new DocumentService())->processDocument($document->id);

            $processed += 1;
        }

        return Log::info("Processed $processed Documents");
    }
}
