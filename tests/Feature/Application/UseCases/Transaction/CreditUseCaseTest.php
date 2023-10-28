<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\CreditUseCase;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use Costa\Entity\ValueObject\Uuid;
use Tests\Stubs\DatabaseTransaction;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainPixKey;

describe("CreditUseCase Feature Test", function () {
    test("create a new entity", function () {
        $pixKey = new DomainPixKey(...arrayDomainPixKey(['balance' => 10]));

        $pixKeyRepository = new PixKeyRepository();
        $pixKeyRepository->create($pixKey);

        $accountRepository = new AccountRepository();
        $accountRepository->create($pixKey->account);

        $useCase = new CreditUseCase(
            transactionRepository: new TransactionRepository(),
            pixKeyRepository: $pixKeyRepository,
            accountRepository: $accountRepository,
            eventManager: new EventManager(),
            databaseTransaction: new DatabaseTransaction(),
        );

        $response = $useCase->exec(
            (string)Uuid::make(),
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::COMPLETED);
        assertEquals(60, $pixKey->account->balance);
    });
});