<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\CreateUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\dataDomainAccount;

describe("CreateUseCase Feature Test", function () {
    test("create a new entity", function () {
        $account = new DomainAccount(...dataDomainAccount());

        $accountRepository = new AccountRepository();
        $accountRepository->create($account);

        $useCase = new CreateUseCase(
            transactionRepository: new TransactionRepository(),
            accountRepository: $accountRepository,
            eventManager: new EventManager(),
        );

        $response = $useCase->exec(
            $account->id(),
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::PENDING);
    });
});