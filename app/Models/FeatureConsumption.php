<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\FeatureConsumption as SoulFeatureConsumption;

class FeatureConsumption extends SoulFeatureConsumption
{
    use HasFactory, HasUuids;

    protected $guarded = [];
}
