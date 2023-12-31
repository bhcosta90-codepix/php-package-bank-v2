<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Enum;

enum EnumTransactionStatus: string
{
    case OPEN = 'open';

    case PENDING = 'pending';

    case CONFIRMED = 'confirmed';

    case COMPLETED = 'completed';

    case ERROR = 'error';
}
