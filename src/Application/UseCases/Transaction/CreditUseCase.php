<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;
use Throwable;

class CreditUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected AccountRepositoryInterface $accountRepository,
        protected EventManagerInterface $eventManager,
        protected DatabaseTransactionInterface $databaseTransaction,
    ) {
        //
    }

    /**
     * @throws UseCaseException
     * @throws NotificationException
     * @throws DomainNotFoundException
     * @throws EntityException
     * @throws Throwable
     */
    public function exec(
        string $id,
        string $description,
        float $value,
        string $kind,
        string $key
    ): DomainTransaction {
        if (!$domainPix = $this->pixKeyRepository->find($kind = EnumPixType::from($kind), $key)) {
            throw new DomainNotFoundException(DomainAccount::class, $key . " and kind: {$kind->value}");
        }

        $response = new DomainTransaction(
            account: $domainPix->account,
            reference: new Uuid($id),
            description: $description,
            value: $value,
            kind: $kind,
            key: $key,
            type: EnumTransactionType::CREDIT
        );

        $response->confirmed()->completed();

        try {
            if (($response = $this->transactionRepository->create($response)) && $this->accountRepository->save(
                    $domainPix->account
                )) {
                $this->databaseTransaction->commit();
                $this->eventManager->dispatch($response->getEvents());
                return $response;
            }
        } catch (Throwable $e) {
            $this->databaseTransaction->rollback();
            throw $e;
        }

        throw new UseCaseException(
            "We were unable to register this transaction in our database"
        );
    }
}