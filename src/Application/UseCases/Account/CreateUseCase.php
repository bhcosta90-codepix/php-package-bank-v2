<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Account;

use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
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
     */
    public function exec(string $name): DomainAccount
    {
        $response = new DomainAccount(
            name: $name,
        );

        if ($response = $this->accountRepository->create($response)) {
            return $response;
        }

        throw new UseCaseException(
            "Unable to register this account"
        );
    }
}