<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Repository;

use CodePix\Bank\Domain\DomainAccount;

interface AccountRepositoryInterface
{
    public function find(string $id): ?DomainAccount;

    public function create(DomainAccount $entity): ?DomainAccount;

    public function save(DomainAccount $entity): ?DomainAccount;
}