<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCases\PixKey;

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;

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