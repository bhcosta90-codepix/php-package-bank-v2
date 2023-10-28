<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction;

use BRCas\CA\Contracts\Items\PaginationInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;

class MyTransactionUseCase
{
    public function __construct(protected TransactionRepositoryInterface $transactionRepository)
    {
    }

    public function exec(string $account): PaginationInterface
    {
        return $this->transactionRepository->myTransactions($account);
    }
}