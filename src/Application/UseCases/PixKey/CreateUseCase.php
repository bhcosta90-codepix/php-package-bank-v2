<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCases\PixKey;

use BRCas\CA\Exceptions\UseCaseException;
use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;

class CreateUseCase
{
    public function __construct(protected PixKeyRepositoryInterface $pixKeyRepository)
    {
        //
    }

    /**
     * @throws NotificationException
     * @throws UseCaseException
     * @throws EntityException
     */
    public function exec(string $kind, string $key): DomainPixKey
    {
        $kind = EnumPixType::from($kind);
        $response = new DomainPixKey(
            kind: $kind,
            key: $key,
        );

        if ($this->pixKeyRepository->find($kind, $key)) {
            throw new EntityException("This pix is already registered in our database");
        }

        if ($response = $this->pixKeyRepository->create($response)) {
            return $response;
        }

        throw new UseCaseException(
            "We were unable to register this pix in our database"
        );
    }
}