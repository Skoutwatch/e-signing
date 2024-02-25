<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\DocumentUpload;
use App\Services\Mixpanel\MixpanelService;
use App\Services\ProcessDocument\HtmlToPdfService;
use App\Services\ProcessDocument\MergePdfService;

class DocumentService
{
    public function createDocument($data)
    {
        $authenticated = auth('api')->user() ? true : false;

        $document = array_key_exists('user_id', $data) ? auth('api')->user()->activeTeam->team->documents()->create($data) : Document::create($data);

        $authenticated ? (new DocumentAuditTrailService(auth('api')->user(), $document))->createDocumentAuditTrail($document['entry_point']) : null;

        return $document;
    }

    public function userDocuments()
    {
        return auth('api')->user()
            ->activeTeam
            ->team->documents()->with('uploads', 'participants')
            ->withCount('participants', 'tools', 'uploads', 'seals')
            ->whereDoesntHave('locker')
            ->orderBy('updated_at', 'ASC')
            ->get();
    }

    public function userDocumentsInShortDetails()
    {
        return auth('api')->user()
            ->activeTeam
            ->team->documents()
            ->where('parent_id', null)
            ->withCount('participants', 'tools', 'uploads', 'seals')
            ->whereDoesntHave('locker')
            ->orderBy('updated_at', 'DESC')
            ->get();
    }

    public function allUserDocumentsInShortDetails()
    {
        return auth('api')->user()
            ->activeTeam
            ->team->allDocuments()
            ->withCount('participants', 'tools', 'uploads', 'seals')
            ->whereDoesntHave('locker')
            ->latest('updated_at')
            ->get();
    }

    public function userSignlinkInShortDetails()
    {
        return auth('api')->user()
            ->activeTeam
            ->team->signlinkDocuments()
            ->withCount('participants', 'tools', 'uploads', 'signlinkUploads', 'seals')
            ->whereDoesntHave('locker')
            ->latest('updated_at')
            ->get();
    }

    public function userDocumentById($id)
    {
        return Document::with('uploads', 'participants')
            ->withCount('participants', 'tools', 'uploads', 'seals', 'signed')
            ->find($id);
    }

    public function userDocumentByParentId($id)
    {
        return Document::with('uploads', 'participants', 'childrenDocuments', 'childrenDocumentUploads', 'allChildrenPreviousDocumentUploads', 'allDocumentUploads')
            ->withCount('participants', 'tools', 'uploads', 'seals', 'signed', 'childrenDocuments', 'childrenDocumentUploads', 'allDocumentUploads')
            ->find($id);
    }

    public function userDocumentByIdStrict($id)
    {
        return Document::with('uploads', 'participants')
            ->withCount('participants', 'tools', 'uploads', 'seals')
            ->whereHas('participants', function ($q) {
                $q->where('user_id', '=', auth('api')->id());
            })->first();
    }

    public function DocumentParticipantsById($id)
    {
        return Document::with('participants', 'user')->find($id);
    }

    public function userByDocumentIdAndEmail($email, $documentID)
    {
        return DocumentParticipant::where('email', $email)
            ->where('document_id', $documentID)
            ->first();
    }

    public function userByDocumentIdEmailOtp($email, $documentID, $otp)
    {
        return DocumentParticipant::where('email', $email)
            ->where('document_id', $documentID)
            ->where('otp', $otp)
            ->first();
    }

    public function userByDocumentIdAndEmaiIncludingOwner($email, $documentID)
    {
        $isAParticipant = DocumentParticipant::where('email', $email)
            ->where('document_id', $documentID)
            ->first() ? true : false;

        $document = Document::find($documentID)?->user?->email == $email ? true : false;

        return $isAParticipant || $document ? true : false;
    }

    public function deleteProperty()
    {
    }

    public function findDocument($id)
    {
        return Document::find($id);
    }

    public function getCompletedDocument($id)
    {
        $document = $this->findDocument($id);

        return DocumentUpload::where('document_id', $document->id)
            ->where('status', 'Completed')
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function processDocument($id)
    {
        $document = Document::findOrFail($id);

        // if($document->status == "Completed"){
        //     return config('externallinks.s3_storage_url').$document->file;
        // }

        $childrenDocuments = $document->childrenDocuments;

        $converted_files = [];

        $merge_childern_files = [];

        $mergedFiles = [];

        $converted_files = [];

        $previousDocuments = [];

        $uploads = $document->childrenPreviousDocumentUploads()->orderBy('number_ordering', 'ASC')->get();

        foreach ($uploads as $upload) {
            if (file_exists($upload->converted_file)) {
                $previousDocuments[] = $upload->converted_file;
            } else {
                $previousDocuments[] = (new HtmlToPdfService())->html($upload, $document);
            }
        }

        foreach ($childrenDocuments as $doc) {

            $files = [];

            foreach ($doc->documentPagesProcessing as $pages) {
                if (file_exists($pages->converted_file)) {
                    $files[] = $pages->converted_file;
                } else {
                    $files[] = (new HtmlToPdfService())->html($pages, $document);
                }
            }

            $mergedFiles['storage'] = (new MergePdfService())->mergePdf($document, $files);

            $checkIfCompletedDocumentExist = $doc->documentPagesCompleted->first();

            $store = $checkIfCompletedDocumentExist
                ? (new DocumentUploadService())->updateUpload($checkIfCompletedDocumentExist, $mergedFiles, $doc, null, 'Completed', null)
                : (new DocumentUploadService())->createUpload($mergedFiles, $doc, null, 'Completed', null);

            $converted_files[] = $store->file_url;
        }

        $combined_file = array_unique(array_merge($previousDocuments, $converted_files));

        $merge_childern_files['storage'] = (new MergePdfService())->mergePdf($document, $combined_file);

        $checkIfParentDocumentHasFinalMergedDocumentExist = $document->documentPagesCompleted?->first();

        $finalMergedDocument = $checkIfParentDocumentHasFinalMergedDocumentExist
                ? (new DocumentUploadService())->updateUpload($checkIfParentDocumentHasFinalMergedDocumentExist, $merge_childern_files, $document, null, 'Completed', null)
                : (new DocumentUploadService())->createUpload($merge_childern_files, $document, null, 'Completed', null);

        (new MixpanelService($document))->documentUploaded($combined_file, $document);

        return config('externallinks.s3_storage_url').$finalMergedDocument->file;
    }

    public function userDeclinedDocument($id)
    {
        $document = $this->findDocument($id);

        return DocumentParticipant::where('document_id', $document->id)
            ->where('status', 'Declined')
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function allUserDeclinedDocuments()
    {
        $user = auth('api')->user();

        return DocumentParticipant::where('user_id', $user->id)
            ->where('status', 'Declined')
            ->orderBy('updated_at', 'ASC')
            ->get();
    }

    public function uploadIfExist($doc, $mergedFiles, $page)
    {
        $doc->completedDocuments->where('number_ordering', $page)?->first()?->update([
            'file_url' => $mergedFiles['storage'],
            'file' => $mergedFiles['storage'],
            'number_ordering' => $page,
        ]);

        return $doc->completedDocument;
    }

    public function createDocumentsViaNumbering(array $files): bool
    {
        $numberOrdering = 0;

        foreach ($files as $file) {

            $property = [
                'title' => $file['title'],
                'user_id' => auth('api')->id(),
                'public' => true,
                'entry_point' => $file['entry_point'],
            ];

            $document = $this->createDocument(array_merge($property));

            $file['parent_id'] ? (new DocumentConvService())->collectAllRequest(['files' => [$file['file']]], $document, null, $numberOrdering) : null;

            $initiateNumberOrdering = $document->documentPagesProcessing ? $document->documentPagesProcessing()->orderBy('number_ordering', 'desc')->first()?->number_ordering : 1;

            $numberOrdering += $initiateNumberOrdering;
        }

        return true;
    }
}
