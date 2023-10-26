<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Enum;

enum EnumTransactionType: int
{
    case CREDIT = 0;

    case DEBIT = 1;
}
