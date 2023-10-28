<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use BRCas\CA\Contracts\Items\PaginationInterface;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use Tests\Stubs\Items\PaginationPresenter;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @var DomainAccount[]
     */
    private array $data = [];

    public function create(DomainAccount $entity): ?DomainAccount
    {
        $this->data[$entity->id()] = $entity;
        return $entity;
    }

    public function save(DomainAccount $entity): ?DomainAccount
    {
        if ($this->find($entity->id())) {
            return $entity;
        }

        return null;
    }

    public function find(string $id): ?DomainAccount
    {
        foreach ($this->data as $data) {
            if ((string)$data->id == $id) {
                return $data;
            }
        }
        return null;
    }

    public function myTransactions(DomainAccount $entity): PaginationInterface
    {
        dd('TODO: Implement myTransactions() method.');
    }

    public function myPixKeys(DomainAccount $entity): PaginationInterface
    {
        dd('TODO: Implement myPixKeys() method.');
    }
}