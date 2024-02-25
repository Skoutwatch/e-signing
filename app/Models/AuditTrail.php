<?php

namespace App\Models;

use App\Http\Resources\Audit\AuditTrailCollection;
use App\Http\Resources\Audit\AuditTrailResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = AuditTrailResource::class;

    public $allItems = AuditTrailCollection::class;
}
