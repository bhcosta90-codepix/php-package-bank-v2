<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\Status\ConfirmedUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;

use function Tests\arrayDomainTransaction;
use function Tests\mockTimes;


beforeEach(function(){
    $this->mockDomainTransaction = new DomainTransaction(...arrayDomainTransaction());
});

describe("ConfirmedUseCase Unit Test", function () {
    test("save a transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $this->mockDomainTransaction);
        mockTimes($transactionRepository, 'save', $this->mockDomainTransaction);

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, "dispatch");

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'save', $this->mockDomainTransaction->account);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
        );
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception when find a transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find');

        $mockEventManager = mock(EventManagerInterface::class);

        $accountRepository = mock(AccountRepositoryInterface::class);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
        );
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainTransaction::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });

    test("exception when save a transaction", function () {
        $mockDomainTransaction = mock(DomainTransaction::class);

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $this->mockDomainTransaction);
        mockTimes($transactionRepository, 'save');

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, "dispatch");

        $mockAccount = mock(DomainAccount::class);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'save', $mockAccount);

        $useCase = new ConfirmedUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            eventManager: $mockEventManager,
        );

        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new UseCaseException("An error occurred while saving this transaction")
        );
    });
});