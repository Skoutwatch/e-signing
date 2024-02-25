<?php

namespace App\Models;

use App\Http\Resources\Plan\PlanBenefitCollection;
use App\Http\Resources\Plan\PlanBenefitResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanBenefit extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = PlanBenefitResource::class;

    public $allItems = PlanBenefitCollection::class;

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
