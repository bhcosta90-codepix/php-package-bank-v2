<?php

declare(strict_types=1);

namespace Tests\Stubs\Repository;

use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;

class PixKeyRepository implements PixKeyRepositoryInterface
{
    /**
     * @var DomainPixKey $data
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