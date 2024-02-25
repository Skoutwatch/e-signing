<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * Like Compliance Questions
 */
final class QuestionAnswerStatus extends Enum
{
    const No = 'No';

    const Yes = 'Yes';
}
