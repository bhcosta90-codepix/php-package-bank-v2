<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Account;

use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\ValueObject\Document;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;

class CreateUseCase
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
    ) {
        //
    }

    /**
     * @throws NotificationException
     * @throws UseCaseException
     * @throws EntityException
     */
    public function exec(string $name, string $document): DomainAccount
    {
        if ($this->accountRepository->findByDocument($document)) {
            throw new EntityException('This document already exist in database');
        }

        $response = new DomainAccount(
            name: $name,
            document: new Document($document),
        );

        if ($response = $this->accountRepository->create($response)) {
            return $response;
        }

        throw new UseCaseException(
            "Unable to register this account"
        );
    }
}