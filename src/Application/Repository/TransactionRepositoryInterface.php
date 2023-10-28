<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Repository;

use BRCas\CA\Contracts\Items\PaginationInterface;
use CodePix\Bank\Domain\DomainTransaction;

interface TransactionRepositoryInterface
{
    public function find(string $id): ?DomainTransaction;

    public function create(DomainTransaction $entity): ?DomainTransaction;

    public function save(DomainTransaction $entity): ?DomainTransaction;

    public function myTransactions(string $account): PaginationInterface;
}