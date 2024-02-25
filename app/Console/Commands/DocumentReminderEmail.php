<?php

namespace App\Console\Commands;

use App\Events\DocumentReminderEvent;
use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DocumentReminderEmail extends Command
{
    protected $signature = 'documents:send-reminders';

    protected $description = 'Send email reminders to signers before the document scheduled session is completed';

    public function handle()
    {
        $now = Carbon::now();

        $documents = Document::with(['participants', 'scheduleSession'])
            ->whereHas('participants', function ($query) {
                $query->where('role', 'signer');
            })
            ->whereHas('scheduleSession', function ($query) use ($now) {
                $query->where('status', '<>', 'completed')
                    ->where('date', '<=', $now);
            })
            ->get();
        $documents->each(function ($document) use ($now) {
            $daysUntilSession = $now->diffInDays($document->scheduleSession->date);
            if ($daysUntilSession >= 1) {
                $signerParticipants = $document->participants
                    ->where('role', 'signer');

                foreach ($signerParticipants as $participant) {
                    event(new DocumentReminderEvent($participant, $document, $daysUntilSession));
                }
            }
        });

        Log::info('Document reminder completed successfully.');
    }
}
