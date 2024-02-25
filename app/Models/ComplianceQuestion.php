<?php

namespace App\Models;

use App\Http\Resources\Compliance\ComplianceQuestionCollection;
use App\Http\Resources\Compliance\ComplianceQuestionResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = ComplianceQuestionResource::class;

    public $allItems = ComplianceQuestionCollection::class;

    public function compliance()
    {
        return $this->morphTo();
    }
}
