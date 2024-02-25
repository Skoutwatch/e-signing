<?php

namespace App\Models;

use App\Http\Resources\Plan\FeatureCollection;
use App\Http\Resources\Plan\FeatureResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Feature as SoulFeature;

class Feature extends SoulFeature
{
    use HasFactory, HasUuids;

    public $oneItem = FeatureResource::class;

    public $allItems = FeatureCollection::class;
}
