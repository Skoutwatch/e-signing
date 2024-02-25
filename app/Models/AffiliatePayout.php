<?php

namespace App\Models;

use App\Enums\AffiliatePayoutStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperAffiliatePayout
 */
class AffiliatePayout extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    /*
     * Accessors and mutators
     */

    protected function statusString(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => AffiliatePayoutStatus::getDescription($attributes['status']),
        );
    }

    /*
     * Relationships
     */

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function bankDetail(): BelongsTo
    {
        return $this->belongsTo(BankDetail::class);
    }
}
