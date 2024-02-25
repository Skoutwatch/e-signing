<?php

namespace App\Models;

use App\Http\Resources\Document\DocumentCollection;
use App\Http\Resources\Document\DocumentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $oneItem = DocumentResource::class;

    public $allItems = DocumentCollection::class;

    protected $guarded = [];

    protected $withCount = ['participants'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function childrenDocuments()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    public function documentPagesProcessing()
    {
        return $this->hasMany(DocumentUpload::class)->where('status', 'Processing');
    }

    public function documentPagesCompleted()
    {
        return $this->hasMany(DocumentUpload::class)->where('status', 'Completed');
    }

    public function uploads()
    {
        return $this->hasManyThrough(
            DocumentUpload::class,
            Document::class,
            'parent_id',
            'document_id',
            'id',
            'id'
        )->where('document_uploads.status', 'Processing')->orderBy('number_ordering', 'ASC');
    }

    public function childrenDocumentUploads()
    {
        return $this->hasManyThrough(
            DocumentUpload::class,
            Document::class,
            'parent_id',
            'document_id',
            'id',
            'id'
        );
    }

    public function processingChildrenDocumentUploads()
    {
        return $this->hasManyThrough(
            DocumentUpload::class,
            Document::class,
            'parent_id',
            'document_id',
            'id',
            'id'
        )->where('document_uploads.status', 'Processing')->orderBy('number_ordering', 'ASC');
    }

    public function children()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    public function documentUploads()
    {
        return $this->hasMany(DocumentUpload::class, 'document_id')
            ->where('status', 'Processing')
            ->orderBy('number_ordering', 'ASC');
    }

    public function allDocumentUploads()
    {
        return $this->hasManyThrough(
            DocumentUpload::class,
            Document::class,
            'parent_id',
            'document_id',
            'id',
            'id'
        )->where('document_uploads.status', 'Processing')->orderBy('number_ordering', 'ASC');
    }

    public function getAllDocumentUploadsAttribute()
    {
        return $this->mergeDocumentUploads($this);
    }

    protected function mergeDocumentUploads($document)
    {
        $uploads = $document->documentUploads;

        foreach ($document->children as $child) {
            $uploads = $uploads->merge($this->mergeDocumentUploads($child));
        }

        return $uploads;
    }

    public function childrenPreviousDocumentUploads()
    {
        return $this->hasMany(DocumentUpload::class)->where('status', 'Processing');
    }

    public function allChildrenPreviousDocumentUploads()
    {
        return $this->hasMany(DocumentUpload::class);
    }

    public function fromLastDocumentOrdering()
    {
        return $this->processingChildrenDocumentUploads()->where('number_ordering', 'DESC')->first();
    }

    public function orderUploads()
    {
        return $this->hasMany(DocumentUpload::class)->where('status', 'Processing')->whereNotNull('number_ordering')->orderBy('number_ordering', 'ASC');
    }

    public function orderUploadPage()
    {
        return $this->hasOne(DocumentUpload::class)->where('status', 'Processing')->whereNotNull('number_ordering')->orderBy('number_ordering', 'ASC');
    }

    public function signlinkUploads()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    public function parentDocument()
    {
        return $this->belongsTo(Document::class, 'parent_id', 'id');
    }

    public function signlinkResponses()
    {
        return $this->hasMany(Document::class, 'parent_id')->where('status', 'Completed');
    }

    public function completedDocument()
    {
        return $this->hasOne(DocumentUpload::class)->where('status', 'Completed');
    }

    public function completedDocuments()
    {
        return $this->hasMany(DocumentUpload::class)->where('status', 'Completed');
    }

    public function finalDocument()
    {
        return $this->hasOne(DocumentUpload::class)->where('number_ordering', null)->where('status', 'Completed');
    }

    public function participants()
    {
        return $this->hasMany(DocumentParticipant::class);
    }

    public function tools()
    {
        return $this->hasManyThrough(
            DocumentResourceTool::class,
            DocumentUpload::class,
            'document_id',
            'document_upload_id',
            'id',
            'id'
        );
    }

    public function seals()
    {
        return $this->hasMany(DocumentResourceTool::class, 'document_id')->whereIn('tool_name', ['Seal']);
    }

    public function signlinkTools()
    {
        return $this->hasMany(DocumentResourceTool::class, 'document_id');
    }

    public function toolImages()
    {
        return $this->hasManyThrough(
            DocumentResourceTool::class,
            DocumentUpload::class,
            'document_id',
            'document_upload_id',
            'id',
            'id'
        )->where('tool_name', 'Photo');
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function scheduleSessions()
    {
        return $this->morphMany(ScheduleSession::class, 'schedule');
    }

    public function scheduleSession()
    {
        return $this->morphOne(ScheduleSession::class, 'schedule');
    }

    public function signedTools()
    {
        return $this->hasManyThrough(
            DocumentResourceTool::class,
            DocumentUpload::class,
            'document_id',
            'document_upload_id',
            'id',
            'id'
        )->whereNotNull('append_print_id');
    }

    public function unsignedTools()
    {
        return $this->hasManyThrough(
            DocumentResourceTool::class,
            DocumentUpload::class,
            'document_id',
            'document_upload_id',
            'id',
            'id'
        )->whereNull('append_print_id');
    }

    public function findNotSignedToolsFromSignedColumn()
    {
        return $this->hasManyThrough(
            DocumentResourceTool::class,
            DocumentUpload::class,
            'document_id',
            'document_upload_id',
            'id',
            'id'
        )->where('signed', false);
    }

    public function signed()
    {
        return $this->hasMany(DocumentResourceTool::class)->where('signed', true);
    }

    public function unsigned()
    {
        return $this->hasMany(DocumentResourceTool::class)->where('signed', false);
    }

    public function usersigned()
    {
        return $this->hasMany(DocumentResourceTool::class, 'document_id')->where('signed', true)->where('user_id', auth('api')->id());
    }

    public function userunsigned()
    {
        return $this->hasMany(DocumentResourceTool::class, 'document_id')->where('signed', false)->where('user_id', auth('api')->id());
    }

    public function userIsAParticipant()
    {
        return $this->hasOne(DocumentParticipant::class)->where('user_id', auth('api')->id());
    }

    public function scopeIsAQuickRequestNotaryAndAffidavitsDocument(Builder $builder)
    {
        return $builder->where('public', false);
    }

    public function scopeIsADocument(Builder $builder)
    {
        return $builder->where('is_a_template', false)->where('is_a_signlink_docs', false)->where('public', true);
    }

    public function scopeIsATemplate(Builder $builder)
    {
        return $builder->where('is_a_template', true)->where('is_a_signlink_docs', false)->where('public', true);
    }

    public function scopeIsASignLinkDocument(Builder $builder)
    {
        return $builder->where('is_a_template', false)
            ->whereNull('parent_id')
            ->where('is_a_signlink_docs', true)
            ->where('public', true);
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedback');
    }

    public function userFormData()
    {
        return $this->hasOne(SignlinkDocumentUserFormData::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class, 'subject_id')->where('subject_type', 'Document')->orderBy('created_at', 'ASC');
    }

    public function locker()
    {
        return $this->hasMany(DocumentLocker::class)->where('user_id', auth('api')->id());
    }

    public function signers()
    {
        return $this->hasMany(DocumentParticipant::class)->where('role', 'Signer');
    }

    public function approvers()
    {
        return $this->hasMany(DocumentParticipant::class)->where('role', 'Approver');
    }

    public function viewer()
    {
        return $this->hasMany(DocumentParticipant::class)->where('role', 'Viewer');
    }

    public function declinedparticipant()
    {
        return $this->hasMany(DocumentParticipant::class)->where('Status', 'Declined');
    }
}
