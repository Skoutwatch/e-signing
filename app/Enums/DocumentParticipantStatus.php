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
 *
 * document_participants
 */
final class DocumentParticipantStatus extends Enum
{
    const Pending = 'Pending';

    const Sent = 'Sent';

    const Signed = 'Signed';

    const Approved = 'Approved';

    const Declined = 'Declined';

    const Empty = '';

    const Nothing = null;
}
