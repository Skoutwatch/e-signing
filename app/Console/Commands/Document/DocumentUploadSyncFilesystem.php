<?php

namespace App\Console\Commands\Document;

use App\Models\Document;
use App\Enums\DocumentStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Document\DocumentService;

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

    public function handle()
    {
        $documents = Document::where('status', '!=',DocumentStatus::Completed)->get();

        $processed = 0;

        foreach ($documents as $document) {
            try {
                (new DocumentService())->processDocument($document->id);
                $processed++;
            } catch (\Exception $e) {
                Log::error("An error occurred while processing document {$document->id}: " . $e->getMessage());
            }
        }

        return Log::info("Processed $processed Documents");
    }
}
