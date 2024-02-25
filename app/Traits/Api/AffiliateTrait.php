<?php

namespace App\Traits\Api;

use App\Models\Affiliate;

trait AffiliateTrait
{
    private Affiliate $affiliate;

    private function getAffiliate(): void
    {
        if (! request()?->user()->affiliate instanceof Affiliate) {
            abort(403, 'You are not an affiliate');
        }

        $this->affiliate = request()?->user()->affiliate;
    }
}
