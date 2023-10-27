<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\Status\ConfirmedUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;

use function Tests\arrayDomainAccount;
use function Tests\mockTimes;

describe("ConfirmedUseCase Unit Test", function () {
    test("save a transaction", function () {
        $mockDomainTransaction = $this->createMock(DomainTransaction::class);
        $mockDomainTransaction->method('__get')
            ->with('account')
            ->willReturn($account = mock(DomainAccount::class));

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save', $mockDomainTransaction);

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, "dispatch");

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'save', $account);

        $mockDatabaseTransactionInterface = mock(DatabaseTransactionInterface::class);
        mockTimes($mockDatabaseTransactionInterface, 'commit');

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
            databaseTransaction: $mockDatabaseTransactionInterface,
        );
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception commit to database transaction", function () {
        $mockDomainTransaction = $this->createMock(DomainTransaction::class);
        $mockDomainTransaction->method('__get')
            ->with('account')
            ->willReturn($account = mock(DomainAccount::class));

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save', $mockDomainTransaction);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'save', $mockDomainTransaction->account);

        $mockDatabaseTransactionInterface = mock(DatabaseTransactionInterface::class);
        mockTimes($mockDatabaseTransactionInterface, 'rollback');
        $mockDatabaseTransactionInterface->shouldReceive('commit')->andThrow(new Exception());

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: mock(EventManagerInterface::class),
            databaseTransaction: $mockDatabaseTransactionInterface,
        );
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(new Exception());
    });

    test("exception when find a transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find');

        $mockEventManager = mock(EventManagerInterface::class);

        $accountRepository = mock(AccountRepositoryInterface::class);

        $mockDatabaseTransactionInterface = mock(DatabaseTransactionInterface::class);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
            databaseTransaction: $mockDatabaseTransactionInterface,
        );
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainTransaction::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });

    test("exception when save a transaction", function () {
        $mockDomainTransaction = $this->createMock(DomainTransaction::class);
        $mockDomainTransaction->method('__get')
            ->with('account')
            ->willReturn($account = mock(DomainAccount::class));

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save');

        $mockEventManager = mock(EventManagerInterface::class);

        $accountRepository = mock(AccountRepositoryInterface::class);

        $databaseTransaction = mock(DatabaseTransactionInterface::class);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
            databaseTransaction: $databaseTransaction,
        );

        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new UseCaseException("An error occurred while saving this transaction")
        );
    });
});