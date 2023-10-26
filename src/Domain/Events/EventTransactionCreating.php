<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Events;

use CodePix\System\Domain\DomainTransaction;
use Costa\Entity\Contracts\EventInterface;

class EventTransactionCreating implements EventInterface
{
    public function __construct(protected DomainTransaction $transaction)
    {
        //
    }

    public function payload(): array
    {
        return $this->transaction->toArray();
    }
}