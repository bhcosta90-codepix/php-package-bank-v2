<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCases\Transaction;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumPixType;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;

class DebitUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected AccountRepositoryInterface $accountRepository,
        protected EventManagerInterface $eventManager,
    ) {
        //
    }

    /**
     * @throws UseCaseException
     * @throws NotificationException
     * @throws DomainNotFoundException
     */
    public function exec(
        string $account,
        string $description,
        float $value,
        string $kind,
        string $key
    ): DomainTransaction {
        if (!$accountDb = $this->accountRepository->find($account)) {
            throw new DomainNotFoundException(DomainAccount::class, $account);
        }

        $kind = EnumPixType::from($kind);

        $response = new DomainTransaction(
            account: $accountDb,
            reference: null,
            description: $description,
            value: $value,
            kind: $kind,
            key: $key,
        );
        $response->pending();

        if ($response = $this->transactionRepository->create($response)) {
            $this->eventManager->dispatch($response->getEvents());
            return $response;
        }

        throw new UseCaseException(
            "We were unable to register this transaction in our database"
        );
    }
}