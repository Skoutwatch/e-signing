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
 * document_template
 */
final class DocumentParticipantRole extends Enum
{
    const Signer = 'Signer';

    const Approver = 'Approver';

    const Viewer = 'Viewer';

    const Notary = 'Notary';

    const Empty = '';

    const Nothing = null;
}
