<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Repository;

use BRCas\CA\Contracts\Items\PaginationInterface;
use CodePix\Bank\Domain\DomainAccount;

interface AccountRepositoryInterface
{
    public function find(string $id, $lock = false): ?DomainAccount;

    public function create(DomainAccount $entity): ?DomainAccount;

    public function save(DomainAccount $entity): ?DomainAccount;

    public function myTransactions(DomainAccount $entity): PaginationInterface;

    public function myPixKeys(DomainAccount $entity): PaginationInterface;
}