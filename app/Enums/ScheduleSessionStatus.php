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
final class ScheduleSessionStatus extends Enum
{
    const Pending = 'Pending';

    const Awaiting = 'Awaiting';

    const Accepted = 'Accepted';

    const Completed = 'Completed';
}
