<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\Status\CompleteUseCase;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Tests\Stubs\DatabaseTransaction;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainTransaction;


describe("CompleteUseCase Feature Test", function () {
    test("save a transaction", function () {
        $transaction = new DomainTransaction(...arrayDomainTransaction(EnumTransactionType::DEBIT, ['balance' => 10]));
        $transaction->pending();

        $accountRepository = new AccountRepository();
        $accountRepository->create($transaction->account);

        $transactionRepository = new TransactionRepository();
        $transactionRepository->create($transaction);

        $useCase = new CompleteUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: new EventManager(),
            databaseTransaction: new DatabaseTransaction(),
        );
        $response = $useCase->exec($transaction->id());
        assertEquals(EnumTransactionStatus::COMPLETED, $response->status);
        assertEquals(-40, $response->account->balance);
    });
});