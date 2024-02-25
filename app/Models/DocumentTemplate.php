<?php

namespace App\Models;

use App\Http\Resources\Document\DocumentTemplateCollection;
use App\Http\Resources\Document\DocumentTemplateResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = DocumentTemplateResource::class;

    public $allItems = DocumentTemplateCollection::class;

    protected $guarded = [];

    public function templatable()
    {
        return $this->morphTo();
    }
}
