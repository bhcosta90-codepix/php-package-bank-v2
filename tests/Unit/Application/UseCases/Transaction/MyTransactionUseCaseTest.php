<?php

declare(strict_types=1);

use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\MyTransactionUseCase;

use function Tests\mockTimes;

describe("MyTransactionUseCase Unit Test", function () {
    test("execute", function () {
        $mock = mock(TransactionRepositoryInterface::class);
        mockTimes($mock, 'myTransactions');

        $useCase = new MyTransactionUseCase($mock);
        $useCase->exec('testing');
    });
});