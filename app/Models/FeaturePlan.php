<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\FeaturePlan as SoulFeaturePlan;

class FeaturePlan extends SoulFeaturePlan
{
    use HasFactory, HasUuids;

    protected $table = 'feature_plan';
}
