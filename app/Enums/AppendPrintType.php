<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AppendPrintType extends Enum
{
    const Date = 'Date';

    const Fullname = 'Fullname';

    const Initial = 'Initial';

    const Signature = 'Signature';

    const NotaryStamp = 'NotaryStamp';

    const NotaryTraditionalSeal = 'NotaryTraditionalSeal';

    const NotaryDigitalSeal = 'NotaryDigitalSeal';

    const CompanyStamp = 'CompanyStamp';

    const CompanySeal = 'CompanySeal';

    const Photograph = 'Photograph';

    const Camera = 'Camera';

    const LeftThumbFinger = 'LeftThumbFinger';

    const LeftPointerFinger = 'LeftPointerFinger';

    const LeftMiddleFinger = 'LeftMiddleFinger';

    const LeftRingFinger = 'LeftRingFinger';

    const LeftPinkyFinger = 'LeftPinkyFinger';

    const RightThumbFinger = 'RightThumbFinger';

    const RightPointerFinger = 'RightPointerFinger';

    const RightMiddleFinger = 'RightMiddleFinger';

    const RightRingFinger = 'RightRingFinger';

    const RightPinkyFinger = 'RightPinkyFinger';

    const Text = 'Text';
}
