<?php

namespace App\Models;

use App\Http\Resources\Compliance\ComplianceResponseCollection;
use App\Http\Resources\Compliance\ComplianceResponseResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceResponse extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = ComplianceResponseResource::class;

    public $allItems = ComplianceResponseCollection::class;
}
