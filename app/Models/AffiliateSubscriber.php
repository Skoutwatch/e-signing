<?php

namespace App\Models;

use App\Enums\AffiliateSubscriberStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperAffiliateSubscriber
 */
class AffiliateSubscriber extends Model
{
    use HasFactory;

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    protected $guarded = [];

    /*
     * Accessors and mutators
     */

    protected function statusString(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => AffiliateSubscriberStatus::getDescription($attributes['status']),
        );
    }

    /*
     * Relationships
     */

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function plan(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
