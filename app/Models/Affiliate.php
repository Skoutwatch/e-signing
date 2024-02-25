<?php

namespace App\Models;

use App\Enums\AffiliateDiscountType;
use App\Enums\AffiliatePartnerType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*
     * Accessors and mutators
     */

    protected function discountTypeString(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => AffiliateDiscountType::getDescription($attributes['discount_type']),
        );
    }

    protected function partnerTypeString(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => AffiliatePartnerType::getDescription($attributes['partner_type']),
        );
    }

    /*
     * Relationships
     */

    public function earnings(): HasMany
    {
        return $this->hasMany(AffiliateEarning::class, 'affiliate_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(AffiliatePayout::class, 'affiliate_id');
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(AffiliateSubscriber::class, 'affiliate_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
