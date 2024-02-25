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
final class ScheduleSessionRequestStatus extends Enum
{
    const Pending = 'Pending';

    const Accepted = 'Accepted';

    const Rejected = 'Rejected';

    const Awaiting = 'Awaiting';

    const Closed = 'Closed';
}
