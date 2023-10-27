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

class ConfirmedUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected AccountRepositoryInterface $accountRepository,
        protected EventManagerInterface $eventManager,
        protected DatabaseTransactionInterface $databaseTransaction,
    ) {
        //
    }

    /**
     * @throws EntityException
     * @throws UseCaseException
     * @throws DomainNotFoundException
     * @throws Throwable
     */
    public function exec(string $id): DomainTransaction
    {
        $response = $this->transactionRepository->find($id) ?: throw new DomainNotFoundException(
            DomainTransaction::class,
            $id
        );

        try {
            $response->confirmed();
            if ($this->transactionRepository->save($response) && $this->accountRepository->save($response->account)) {
                $this->databaseTransaction->commit();
                $this->eventManager->dispatch($response->getEvents());
                return $response;
            }
        } catch (Throwable $e) {
            $this->databaseTransaction->rollback();
            throw $e;
        }

        throw new UseCaseException(
            "An error occurred while saving this transaction"
        );
    }
}