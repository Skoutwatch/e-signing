<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 * @method static static OptionFour()
 *
 * document_participants
 * document
 * Schedule session
 */
final class EntryPoint extends Enum
{
    const Docs = 'Docs';

    const Video = 'Video';

    const Notary = 'Notary';

    const Affidavit = 'Affidavit';

    const CFO = 'CFO';
}
