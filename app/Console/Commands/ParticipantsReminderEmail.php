<?php

namespace App\Console\Commands;

use App\Mail\Document\DocumentReminderMail;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ParticipantsReminderEmail extends Command
{
    protected $signature = 'document:check-participants';

    protected $description = 'Check participants who have not signed and trigger events';

    public function handle()
    {
        $documents = Document::where('status', '!=', 'Completed')
            ->where('has_reminder', true)
            ->get();

        $reminderCount = 0;

        foreach ($documents as $document) {

            if ($this->isTimeForReminder($document)) {
                foreach ($document->participants as $participant) {
                    if ($participant->status === 'Sent') {
                        Mail::to($participant->email)->send(new DocumentReminderMail($document, $participant));
                        $reminderCount++;
                        $this->info('Sending reminder to Participant ID: '.$participant->id);
                    }
                }
                $document->touch();
            }
        }

        Log::info($reminderCount.' reminder(s) sent successfully.');
    }

    protected function isTimeForReminder($document)
    {
        $lastReminderSentAt = $document->updated_at ?? $document->created_at;
        $hoursDiff = Carbon::now()->diffInHours($lastReminderSentAt);
        $expectedInterval = 24;

        return $hoursDiff >= $expectedInterval;
    }
}
