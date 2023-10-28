<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Account;

use BRCas\CA\Contracts\Items\PaginationInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;

class MyTransactionUseCase
{
    public function __construct(protected AccountRepositoryInterface $accountRepository)
    {
    }

    /**
     * @throws DomainNotFoundException
     */
    public function exec(string $id): PaginationInterface
    {
        if (!$account = $this->accountRepository->find($id)) {
            throw new DomainNotFoundException(DomainAccount::class, $id);
        }

        return $this->accountRepository->myTransactions($account);
    }
}