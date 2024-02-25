<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use Illuminate\Support\Str;

class AffiliateService
{
    public const REFERRAL_URL = 'https://tonotehq.com/referral/';

    /**
     * Generate unique code for affiliate
     */
    public function generateUniqueCode(string $firstName): string
    {
        $code = strtoupper(Str::snake($firstName).'_'.Str::random(4));

        while (Affiliate::where('code', $code)->count() > 0) {
            $code = strtoupper(Str::snake($firstName).'_'.Str::random(4));
        }

        return $code;
    }

    /**
     * Get an affiliate's referral URL
     */
    public function referralUrl(Affiliate $affiliate): string
    {
        return self::REFERRAL_URL.$affiliate->code;
    }

    /**
     * Return the URL of the affiliate's promo kit
     */
    public static function promoKitUrl(): string
    {
        return asset('affiliate/kit.zip');
    }
}
