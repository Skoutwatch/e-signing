<?php

namespace App\Mail\Schedule;

use App\Models\ScheduleSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleSessionRecordingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ScheduleSession $scheduleSession)
    {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Your Session recording is ready',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.schedule.ScheduleSessionRecordingMail',
        );
    }

    public function attachments()
    {
        return Attachment::fromPath($this->scheduleSession->video_recording_file);
    }
}
