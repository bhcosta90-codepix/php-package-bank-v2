<?php

declare(strict_types=1);

namespace Tests\Stubs;

use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    public function commit(): void
    {
        return;
    }

    public function rollback(): void
    {
        return;
    }
}