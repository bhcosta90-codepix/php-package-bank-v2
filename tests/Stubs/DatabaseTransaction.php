<?php

declare(strict_types=1);

namespace Tests\Stubs;

use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use Closure;

class DatabaseTransactionInterface implements DatabaseTransactionInterface
{
    public function transaction(Closure $closure): void
    {
        $closure();
    }

}