<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\FindUseCase;
use CodePix\Bank\Domain\DomainTransaction;

use function Tests\dataDomainTransaction;
use function Tests\mockTimes;

describe("FindUseCase Unit Test", function () {
    test("get pix", function () {
        $mockDomainTransaction = mock(DomainTransaction::class, dataDomainTransaction());

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find', $mockDomainTransaction);

        $useCase = new FindUseCase(transactionRepository: $transactionRepository);
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception when do not exist a pix", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, 'find');

        $useCase = new FindUseCase(transactionRepository: $transactionRepository);
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainTransaction::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });
});