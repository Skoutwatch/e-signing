<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 * @method static static OptionFour()
 * @method static static OptionFive()
 */
final class TitleType extends Enum
{
    const Card = 'Adding a new Card';
}
