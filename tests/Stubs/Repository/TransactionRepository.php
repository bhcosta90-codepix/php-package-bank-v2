<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use CodePix\System\Application\Repository\TransactionRepositoryInterface;
use CodePix\System\Domain\DomainTransaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var DomainTransaction $data
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