<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\PixKey;

use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Integration\PixKeyIntegrationInterface;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;

class CreateUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected PixKeyIntegrationInterface $pixKeyIntegration,
        protected AccountRepositoryInterface $accountRepository,
    ) {
        //
    }

    /**
     * @throws NotificationException
     * @throws UseCaseException
     * @throws EntityException
     * @throws DomainNotFoundException
     */
    public function exec(string $account, string $kind, ?string $key): DomainPixKey
    {
        $kind = EnumPixType::from($kind);

        if (!$domainAccount = $this->accountRepository->find($account)) {
            throw new DomainNotFoundException(DomainAccount::class, $account);
        }

        if (!$pix = $this->pixKeyIntegration->register($kind, $key)) {
            throw new UseCaseException("The integration with PIX went wrong");
        }

        $response = new DomainPixKey(
            account: $domainAccount,
            kind: $kind,
            key: $pix->key,
        );

        if ($key && $this->pixKeyRepository->find($kind, $key)) {
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