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
final class DocumentResourceTools extends Enum
{
    const Signature = 'Signature';

    const Text = 'Text';

    const TextArea = 'TextArea';

    const Photo = 'Photo';

    const Initial = 'Initial';

    const Seal = 'Seal';

    const Stamp = 'Stamp';
}
