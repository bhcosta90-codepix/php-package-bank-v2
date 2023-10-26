<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction;

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainTransaction;

class FindUseCase
{
    public function __construct(protected TransactionRepositoryInterface $transactionRepository)
    {
        //
    }

    /**
     * @throws DomainNotFoundException
     */
    public function exec(string $id): DomainTransaction
    {
        return $this->transactionRepository->find($id) ?: throw new DomainNotFoundException(
            DomainTransaction::class,
            $id
        );
    }
}