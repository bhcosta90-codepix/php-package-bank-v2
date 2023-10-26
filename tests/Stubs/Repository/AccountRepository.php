<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @var DomainAccount[]
     */
    private array $data = [];

    public function find(string $id): ?DomainAccount
    {
        foreach ($this->data as $data) {
            if ((string)$data->id == $id) {
                return $data;
            }
        }
        return null;
    }

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
}