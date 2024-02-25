<?php

namespace App\Models;

use App\Http\Resources\Document\DocumentUploadCollection;
use App\Http\Resources\Document\DocumentUploadResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = DocumentUploadResource::class;

    public $allItems = DocumentUploadCollection::class;

    protected $guarded = [];

    public function childrenUploads()
    {
        return $this->hasMany(DocumentUpload::class, 'parent_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tools()
    {
        return $this->hasMany(DocumentResourceTool::class, 'document_upload_id');
    }

    public function child()
    {
        return $this->belongsTo(DocumentUpload::class, 'parent_id', 'id');
    }

    public function documentparticipant()
    {
        return $this->hasMany(DocumentParticipant::class, 'document_id');
    }
}
