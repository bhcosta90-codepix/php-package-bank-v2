<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\CreditUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainAccount;
use function Tests\arrayDomainPixKey;

describe("CreditUseCase Feature Test", function () {
    test("create a new entity", function () {
        $pixKey = new DomainPixKey(...arrayDomainPixKey(['balance' => 10]));

        $pixKeyRepository = new PixKeyRepository();
        $pixKeyRepository->create($pixKey);

        $useCase = new CreditUseCase(
            transactionRepository: new TransactionRepository(),
            pixKeyRepository: $pixKeyRepository,
            eventManager: new EventManager(),
        );

        $response = $useCase->exec(
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::CONFIRMED);
        assertEquals(60, $pixKey->account->balance);
    });
});