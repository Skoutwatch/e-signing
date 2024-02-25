<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AffiliatePartnerType extends Enum
{
    const AffiliatePartner = 0;

    const ReferralProgram = 1;

    const StrategicPartner = 2;
}
