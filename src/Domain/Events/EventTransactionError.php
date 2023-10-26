<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Events;

use Costa\Entity\Contracts\EventInterface;
use Costa\Entity\ValueObject\Uuid;

class EventTransactionError implements EventInterface
{
    public function __construct(protected string $id)
    {
        //
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}