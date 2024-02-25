<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 * @method static static OptionFour()
 */
final class PlanPeriodicityType extends Enum
{
    const Week = 'Week';

    const Month = 'Month';

    const Year = 'Year';
}
