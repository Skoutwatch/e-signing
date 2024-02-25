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
 * document_uploads
 */
final class DocumentUploadStatus extends Enum
{
    const Processing = 'Processing';

    const Processed = 'Processed';

    const Sent = 'Sent';

    const Completed = 'Completed';

    const Locked = 'Locked';
}
