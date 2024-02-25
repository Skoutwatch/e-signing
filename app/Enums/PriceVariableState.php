<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 */
final class PriceVariableState extends Enum
{
    const MonetaryValuePrice = 50000;

    const MonetaryValueDocumentAllowance = 0;

    const ExtraNotarySealsPerSessionAmount = 8000;

    const ExtraAffidavitSealsPerSessionAmount = 4000;

    const ExtraCFOSealsPerSessionAmount = 1000;

    const AllowedSeals = 1;
}
