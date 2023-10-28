<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\MyTransactionUseCase;
use CodePix\Bank\Domain\DomainTransaction;
use Costa\Entity\ValueObject\Uuid;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainTransaction;

describe("MyTransactionUseCase Feature Test", function () {
    test("create a new entity", function () {

        $useCase = new MyTransactionUseCase(
            transactionRepository: $repository = new TransactionRepository(),
        );

        $response = $useCase->exec(
            (string)Uuid::make(),
        );

        assertEquals(0, $response->total());
        $repository->create($transaction = new DomainTransaction(...arrayDomainTransaction()));

        $useCase = new MyTransactionUseCase(
            transactionRepository: $repository,
        );

        $response = $useCase->exec(
            (string)Uuid::make(),
        );

        assertEquals(1, $response->total());
        assertEquals($transaction->toArray(), $response->items()[0]);
    });
});