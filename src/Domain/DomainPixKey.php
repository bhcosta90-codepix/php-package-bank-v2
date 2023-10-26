<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain;

use CodePix\Bank\Domain\Enum\EnumPixType;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class DomainPixKey extends Data
{
    public function __construct(
        protected DomainAccount $account,
        protected EnumPixType $kind,
        protected string $key,
    ) {
        parent::__construct();
    }
}