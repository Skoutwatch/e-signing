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
final class DocumentStatus extends Enum
{
    const New = 'New';

    const Sent = 'Sent';

    const Declined = 'Declined';

    const Processing = 'Processing';

    const Completed = 'Completed';

    const Locked = 'Locked';
}
