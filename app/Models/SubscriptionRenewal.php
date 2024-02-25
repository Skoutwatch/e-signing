<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\SubscriptionRenewal as SoulSubscriptionRenewal;

class SubscriptionRenewal extends SoulSubscriptionRenewal
{
    use HasFactory, HasUuids;
}
