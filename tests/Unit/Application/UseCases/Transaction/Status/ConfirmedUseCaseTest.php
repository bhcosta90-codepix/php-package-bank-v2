<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\System\Application\Repository\TransactionRepositoryInterface;
use CodePix\System\Application\UseCases\Transaction\Status\ConfirmedUseCase;
use CodePix\System\Domain\DomainTransaction;

use function Tests\dataDomainTransaction;
use function Tests\mockTimes;


describe("ConfirmedUseCase Unit Test", function () {
    test("save a transaction", function () {
        $mockDomainTransaction = mock(DomainTransaction::class, dataDomainTransaction());
        mockTimes($mockDomainTransaction, 'confirmed');
        mockTimes($mockDomainTransaction, 'getEvents');

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save', $mockDomainTransaction);

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, "dispatch");

        $useCase = new ConfirmedUseCase(transactionRepository: $transactionRepository, eventManager: $mockEventManager);
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception when find a transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find');

        $mockEventManager = mock(EventManagerInterface::class);

        $useCase = new ConfirmedUseCase(transactionRepository: $transactionRepository, eventManager: $mockEventManager);
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainTransaction::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });

    test("exception when save a transaction", function () {
        $mockDomainTransaction = mock(DomainTransaction::class, dataDomainTransaction());
        mockTimes($mockDomainTransaction, 'confirmed');
        mockTimes($mockDomainTransaction, 'getEvents', []);

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save');

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, "dispatch");

        $useCase = new ConfirmedUseCase(transactionRepository: $transactionRepository, eventManager: $mockEventManager);
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new UseCaseException("An error occurred while saving this transaction")
        );
    });
});