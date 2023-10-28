<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction\Status;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainTransaction;
use Costa\Entity\Exceptions\EntityException;
use Throwable;

class ErrorUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
    ) {
        //
    }

    /**
     * @throws EntityException
     * @throws UseCaseException
     * @throws DomainNotFoundException
     * @throws Throwable
     */
    public function exec(string $id, string $message): DomainTransaction
    {
        $response = $this->transactionRepository->find($id) ?: throw new DomainNotFoundException(
            DomainTransaction::class,
            $id
        );

        $response->error($message);

        if ($this->transactionRepository->save($response)) {
            return $response;
        }

        throw new UseCaseException(
            "An error occurred while saving this transaction"
        );
    }
}
