<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;

class CreditUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected EventManagerInterface $eventManager,
    ) {
        //
    }

    /**
     * @throws UseCaseException
     * @throws NotificationException
     * @throws DomainNotFoundException
     * @throws EntityException
     */
    public function exec(
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
            reference: null,
            description: $description,
            value: $value,
            kind: $kind,
            key: $key,
            type: EnumTransactionType::CREDIT
        );

        $response->confirmed();

        if ($response = $this->transactionRepository->create($response)) {
            $this->eventManager->dispatch($response->getEvents());
            return $response;
        }

        throw new UseCaseException(
            "We were unable to register this transaction in our database"
        );
    }
}