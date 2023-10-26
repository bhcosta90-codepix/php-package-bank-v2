<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Repository;

use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;

interface PixKeyRepositoryInterface
{
    public function find(EnumPixType $kind, string $key): ?DomainPixKey;

    public function create(DomainPixKey $entity): ?DomainPixKey;
}