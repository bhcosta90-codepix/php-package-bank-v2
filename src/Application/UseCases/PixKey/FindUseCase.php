<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\PixKey;

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;

class FindUseCase
{
    public function __construct(protected PixKeyRepositoryInterface $pixKeyRepository)
    {
        //
    }

    /**
     * @throws DomainNotFoundException
     */
    public function exec(string $kind, string $key): DomainPixKey
    {
        $kind = EnumPixType::from($kind);

        return $this->pixKeyRepository->find($kind, $key) ?: throw new DomainNotFoundException(
            DomainPixKey::class,
            "kind: {$kind->value} and key: {$key}"
        );
    }
}