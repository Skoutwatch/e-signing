<?php

namespace App\Services\Document;

use App\Models\AuditTrail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DocumentAuditTrailService
{
    public function __construct(public User $user, public Document $document)
    {
    }

    public function audit($log)
    {
        AuditTrail::create([
            'log_name' => $log,
            'subject_id' => $this->document->id,
            'subject_type' => class_basename($this->document),
            'causer_id' => $this->user->id,
            'causer_type' => class_basename($this->user),
        ]);
    }

    public function createDocumentAuditTrail($entryPoint)
    {
        $log = match ($entryPoint) {
            'Docs' => "{$this->user->first_name} {$this->user->last_name} created a document called {$this->document->title}",
            'Notary', 'Affidavit', 'Video', 'CFO' => "{$this->user->first_name} {$this->user->last_name} created a session {$this->document->title}",
        };

        $this->audit($log);
    }

    public function addParticipantAuditTrail($entryPoint, $userAdded, $role)
    {
        $log = match ($entryPoint) {
            'Docs' => "{$userAdded->first_name} {$userAdded->last_name} was added as a {$role} to the document",
            'Notary', 'Affidavit', 'Video', 'CFO' => "{$userAdded->first_name} {$userAdded->last_name} was added as a {$role} to the session",
        };

        $this->audit($log);
    }

    public function removeParticipantAuditTrail($entryPoint, $userAdded)
    {
        $log = match ($entryPoint) {
            'Docs' => "{$userAdded->first_name} {$userAdded->last_name} was removed from the document",
            'Notary', 'Affidavit', 'Video', 'CFO' => "{$userAdded->first_name} {$userAdded->last_name} was removed from the session",
        };

        $this->audit($log);
    }

    public function annotateToolAuditTrail($tool)
    {
        $log = "{$tool->userThatAnnotated->first_name} {$tool->userThatAnnotated->last_name} tagged the document with {$tool->tool_name} field for {$tool->user->first_name} {$tool->user->last_name}";

        $this->audit($log) ? Log::debug('true') : Log::debug('false');
    }

    public function removeToolAuditTrail($tool)
    {
        $log = "{$tool->userThatAnnotated->first_name} {$tool->userThatAnnotated->last_name} removed {$tool->tool_name} field for {$tool->user->first_name} {$tool->user->last_name}";

        $this->audit($log);
    }

    public function signedToolAuditTrail($tool)
    {
        $log = "{$this->user->first_name} {$this->user->last_name} added {$tool?->tool_name}";

        $this->audit($log);
    }

    public function submitDocumentAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} submitted the document";

        $this->audit($log);
    }

    public function participantActionDocumentAuditTrail($action, $comment = null)
    {
        $log = match (strtolower($action)) {
            'declined', => "{$this->user->first_name} {$this->user->last_name} declined the document with comment {$comment}",
            'approved' => "{$this->user->first_name} {$this->user->last_name} $action the document",
            default => null
        };

        $this->audit($log);
    }

    public function declineDocumentAuditTrail($comment = null)
    {
        $log = "{$this->user->first_name} {$this->user->last_name} declined document ".$comment ? "with comment {$comment}" : '';

        $this->audit($log);
    }

    public function completeDocumentAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} marked {$this->document->title} document as complete";

        $this->audit($log);
    }

    public function viewDocumentAuditTrail()
    {
        $log = "Document was viewed by {$this->user->first_name} {$this->user->last_name}";

        $this->audit($log);
    }

    public function joinSessionAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} joined the session";

        $this->audit($log);
    }

    public function leftSessionAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} left the session";

        $this->audit($log);
    }

    public function startSessionAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} started the session";

        $this->audit($log);
    }

    public function endSessionAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} ended the session";

        $this->audit($log);
    }

    public function startRecordingAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} recorded the session";

        $this->audit($log);
    }

    public function endRecordingAuditTrail()
    {
        $log = "{$this->user->first_name} {$this->user->last_name} recorded the session";

        $this->audit($log);
    }
}
