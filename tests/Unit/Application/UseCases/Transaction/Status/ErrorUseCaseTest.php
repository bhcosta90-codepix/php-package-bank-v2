<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\Status\ErrorUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainTransaction;

use function Tests\mockTimes;

describe("ErrorUseCase Unit Test", function () {
    test("save a transaction", function () {
        $mockDomainTransaction = $this->createMock(DomainTransaction::class);
        $mockDomainTransaction->method('__get')
            ->with('account')
            ->willReturn($account = mock(DomainAccount::class));

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save', $mockDomainTransaction);

        $useCase = new ErrorUseCase(
            transactionRepository: $transactionRepository,
        );
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60', 'error');
    });

    test("exception when find a transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find');

        $useCase = new ErrorUseCase(
            transactionRepository: $transactionRepository,
        );
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60', 'error'))->toThrow(
            new DomainNotFoundException(DomainTransaction::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });

    test("exception when save a transaction", function () {
        $mockDomainTransaction = $this->createMock(DomainTransaction::class);
        $mockDomainTransaction->method('__get')
            ->with('account')
            ->willReturn(mock(DomainAccount::class));

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);
        mockTimes($transactionRepository, 'save');

        $useCase = new ErrorUseCase(
            transactionRepository: $transactionRepository,
        );

        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60', 'error'))->toThrow(
            new UseCaseException("An error occurred while saving this transaction")
        );
    });
});