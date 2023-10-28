<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Account;

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;

class FindUseCase
{
    public function __construct(protected AccountRepositoryInterface $accountRepository)
    {
    }

    /**
     * @throws DomainNotFoundException
     */
    public function exec(string $id): DomainAccount
    {
        return $this->accountRepository->find($id) ?: throw new DomainNotFoundException(
            DomainAccount::class,
            $id
        );
    }
}