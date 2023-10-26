<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Enum;

enum EnumPixType: string
{
    case ID = 'id';

    case EMAIL = 'email';

    case DOCUMENT = 'document';

    case PHONE = 'phone';
}
