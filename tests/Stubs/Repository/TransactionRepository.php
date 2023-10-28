<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use BRCas\CA\Contracts\Items\PaginationInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainTransaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var DomainTransaction[]
     */
    private array $data = [];

    public function create(DomainTransaction $entity): ?DomainTransaction
    {
        $this->data[$entity->id()] = $entity;
        return $entity;
    }

    public function save(DomainTransaction $entity): ?DomainTransaction
    {
        if ($this->find($entity->id())) {
            return $entity;
        }

        return null;
    }

    public function myTransactions(string $account): PaginationInterface
    {
        dd('TODO: Implement myTransactions() method.');
    }

    public function find(string $id): ?DomainTransaction
    {
        foreach ($this->data as $data) {
            if ((string)$data->id == $id) {
                return $data;
            }
        }
        return null;
    }


}