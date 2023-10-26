<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;

class PixKeyRepository implements PixKeyRepositoryInterface
{
    /**
     * @var DomainPixKey[]
     */
    private array $data = [];

    public function find(EnumPixType $kind, string $key): ?DomainPixKey
    {
        foreach ($this->data as $data) {
            if ($data->kind == $kind && $data->key == $key) {
                return $data;
            }
        }
        return null;
    }

    public function create(DomainPixKey $entity): ?DomainPixKey
    {
        $this->data[$entity->id()] = $entity;
        return $entity;
    }

}