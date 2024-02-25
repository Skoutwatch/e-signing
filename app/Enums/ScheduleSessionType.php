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
final class ScheduleSessionType extends Enum
{
    const RequestAffidavit = 'Request Affidavit';

    const RequestANotary = 'Request A Notary';

    const RequestVirtualNotarySession = 'Request Virtual Notary Session';

    const Notary = 'Notary';

    const Affidavit = 'Affidavit';

    const CFO = 'CFO';
}
