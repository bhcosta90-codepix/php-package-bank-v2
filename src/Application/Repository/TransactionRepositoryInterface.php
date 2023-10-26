<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Repository;

use CodePix\Bank\Domain\DomainTransaction;

interface TransactionRepositoryInterface
{
    public function find(string $id): ?DomainTransaction;

    public function create(DomainTransaction $entity): ?DomainTransaction;

    public function save(DomainTransaction $entity): ?DomainTransaction;
}