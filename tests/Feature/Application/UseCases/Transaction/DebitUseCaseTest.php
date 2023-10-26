<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\DebitUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;
use function Tests\arrayDomainAccount;
use function Tests\dataDomainAccount;

describe("DebitUseCase Feature Test", function () {
    test("create a new entity", function () {
        $account = new DomainAccount(...arrayDomainAccount());

        $accountRepository = new AccountRepository();
        $accountRepository->create($account);

        $useCase = new DebitUseCase(
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