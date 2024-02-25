<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\FeatureTicket as SoulFeatureTicket;

class FeatureTicket extends SoulFeatureTicket
{
    use HasFactory, HasUuids;
}
