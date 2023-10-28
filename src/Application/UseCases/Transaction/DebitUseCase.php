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
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Costa\Entity\Exceptions\NotificationException;

class DebitUseCase
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected AccountRepositoryInterface $accountRepository,
        protected PixKeyRepositoryInterface $pixKeyRepository,
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
            type: EnumTransactionType::DEBIT,
        );

        $entityDomainPixKey = $this->pixKeyRepository->find($kind, $key);

        if ($entityDomainPixKey && ($entityDomainPixKey->account->id() === $accountDb->id())) {
            $response->error("You cannot transfer to your own account");
        } else {
            $response->pending();
        }

        if ($response = $this->transactionRepository->create($response)) {
            $this->eventManager->dispatch($response->getEvents());
            return $response;
        }

        throw new UseCaseException(
            "We were unable to register this transaction in our database"
        );
    }
}
