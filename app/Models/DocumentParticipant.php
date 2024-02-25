<?php

namespace App\Models;

use App\Http\Resources\Participant\DocumentParticipantCollection;
use App\Http\Resources\Participant\DocumentParticipantResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentParticipant extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = DocumentParticipantResource::class;

    public $allItems = DocumentParticipantCollection::class;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
