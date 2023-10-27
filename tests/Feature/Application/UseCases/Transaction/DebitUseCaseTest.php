<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\Transaction\DebitUseCase;
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

describe("DebitUseCase Feature Test", function () {
    test("create a new entity", function () {
        $account = new DomainAccount(...arrayDomainAccount());

        $accountRepository = new AccountRepository();
        $accountRepository->create($account);

        $pixKeyRepository = new PixKeyRepository();

        $useCase = new DebitUseCase(
            transactionRepository: new TransactionRepository(),
            accountRepository: $accountRepository,
            pixKeyRepository: $pixKeyRepository,
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

    test("create a transaction to same account", function () {
        $account = new DomainAccount(...arrayDomainAccount());
        $pixKey = DomainPixKey::make(['account' => $account] + arrayDomainPixKey());

        $accountRepository = new AccountRepository();
        $accountRepository->create($account);

        $pixKeyRepository = new PixKeyRepository();
        $pixKeyRepository->create($pixKey);

        $useCase = new DebitUseCase(
            transactionRepository: new TransactionRepository(),
            accountRepository: $accountRepository,
            pixKeyRepository: $pixKeyRepository,
            eventManager: new EventManager(),
        );

        $response = $useCase->exec(
            $account->id(),
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::ERROR);
    });
});