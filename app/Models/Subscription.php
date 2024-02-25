<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Scopes\ExpiringWithGraceDaysScope;
use LucasDotVin\Soulbscription\Models\Scopes\StartingScope;
use LucasDotVin\Soulbscription\Models\Scopes\SuppressingScope;
use LucasDotVin\Soulbscription\Models\Subscription as SoulSubscription;

class Subscription extends SoulSubscription
{
    use HasFactory, HasUuids;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['unit', 'transaction_id', 'occurence', 'occurence_limit']);
    }

    /*
     * Relationships
     */

    public function subscriptionable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /*
     * Scopes
     */

    public function scopeBare(Builder $query)
    {
        $query->withoutGlobalScopes([
            ExpiringWithGraceDaysScope::class,
            StartingScope::class,
            SuppressingScope::class,
        ]);
    }
}
