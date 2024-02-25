<?php

namespace App\Models;

use App\Http\Resources\Document\DocumentResourceToolCollection;
use App\Http\Resources\Document\DocumentResourceToolResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentResourceTool extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'document_resources';

    public $oneItem = DocumentResourceToolResource::class;

    public $allItems = DocumentResourceToolCollection::class;

    protected $guarded = [];

    public function upload()
    {
        return $this->belongsTo(DocumentUpload::class, 'document_upload_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userThatAnnotated()
    {
        return $this->belongsTo(User::class, 'who_added_id');
    }

    public function appendPrint()
    {
        return $this->belongsTo(AppendPrint::class);
    }

    public function seals()
    {
        return $this->belongsTo(AppendPrint::class)->where('type', 'NotaryDigitalSeal')->orWhere('type', '=', 'NotaryTraditionalSeal');
    }
}
