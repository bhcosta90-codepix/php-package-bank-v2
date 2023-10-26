<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\System\Application\Repository\TransactionRepositoryInterface;
use CodePix\System\Application\UseCases\Transaction\Status\CompletedUseCase;
use CodePix\System\Domain\DomainTransaction;

use CodePix\System\Domain\Enum\EnumTransactionStatus;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\dataDomainTransaction;
use function Tests\mockTimes;


describe("CompletedUseCase Unit Test", function () {
    test("save a transaction", function () {
        $transaction = new DomainTransaction(...dataDomainTransaction());
        $transaction->pending()->confirmed();

        $transactionRepository = new TransactionRepository();
        $transactionRepository->create($transaction);

        $useCase = new CompletedUseCase(transactionRepository: $transactionRepository, eventManager: new EventManager());
        $response = $useCase->exec($transaction->id());
        assertEquals(EnumTransactionStatus::COMPLETED, $response->status);
    });
});