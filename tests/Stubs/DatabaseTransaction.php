<?php

declare(strict_types=1);

namespace Tests\Stubs;

use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use Closure;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    public function transaction(Closure $closure): mixed
    {
        return $closure();
    }

}