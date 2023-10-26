<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\Status\ConfirmedUseCase;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainTransaction;


describe("ConfirmedUseCase Feature Test", function () {
    test("save a transaction", function () {
        $transaction = new DomainTransaction(...arrayDomainTransaction(EnumTransactionType::DEBIT, ['balance' => 10]));
        $transaction->pending();

        $transactionRepository = new TransactionRepository();
        $transactionRepository->create($transaction);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository, eventManager: new EventManager()
        );
        $response = $useCase->exec($transaction->id());
        assertEquals(EnumTransactionStatus::CONFIRMED, $response->status);
        assertEquals(-40, $response->account->balance);
    });
});