<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Application\Repository\TransactionRepositoryInterface;
use CodePix\Bank\Application\UseCases\Transaction\DebitUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\DomainTransaction;

use function Tests\mockTimes;

describe("DebitUseCase Unit Test", function () {
    test("create a new entity", function () {
        $mockDomainTransaction = mock(DomainTransaction::class);
        mockTimes($mockDomainTransaction, 'getEvents', []);

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, "create", $mockDomainTransaction);

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, 'dispatch');

        $mockAccount = mock(DomainAccount::class);
        mockTimes($mockAccount, "toArray", []);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, "find", $mockAccount);

        $mockPixKeyRepositoryInterface = mock(PixKeyRepositoryInterface::class);
        mockTimes($mockPixKeyRepositoryInterface, 'find');

        $useCase = new DebitUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            pixKeyRepository: $mockPixKeyRepositoryInterface,
            eventManager: $mockEventManager,
        );

        $useCase->exec(
            "af4d8146-c829-46b6-8642-da0a0bdc2884",
            "testing",
            50,
            "email",
            "test@test.com"
        );
    });

    test("exception when account is equal pix account", function () {
        $mockDomainTransaction = mock(DomainTransaction::class);
        mockTimes($mockDomainTransaction, 'getEvents', []);

        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, "create", $mockDomainTransaction);

        $mockEventManager = mock(EventManagerInterface::class);
        mockTimes($mockEventManager, 'dispatch');

        $mockAccount = mock(DomainAccount::class);
        mockTimes($mockAccount, "toArray", []);
        mockTimes($mockAccount, "id", null, 2);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, "find", $mockAccount);

        $mockDomainPixKey = $this->getMockBuilder(DomainPixKey::class)
            ->onlyMethods(['__get'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockDomainPixKey->method('__get')
            ->with('account')
            ->willReturn($mockAccount);

        $mockPixKeyRepositoryInterface = mock(PixKeyRepositoryInterface::class);
        mockTimes($mockPixKeyRepositoryInterface, 'find', $mockDomainPixKey);

        $useCase = new DebitUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            pixKeyRepository: $mockPixKeyRepositoryInterface,
            eventManager: $mockEventManager,
        );

        $useCase->exec(
            "af4d8146-c829-46b6-8642-da0a0bdc2884",
            "testing",
            50,
            "email",
            "test@test.com"
        );
    });

    test("exception when account do not exist", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);

        $mockEventManager = mock(EventManagerInterface::class);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, "find");

        $mockPixKeyRepositoryInterface = mock(PixKeyRepositoryInterface::class);

        $useCase = new DebitUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            pixKeyRepository: $mockPixKeyRepositoryInterface,
            eventManager: $mockEventManager,
        );

        expect(fn() => $useCase->exec(
            "af4d8146-c829-46b6-8642-da0a0bdc2884",
            "testing",
            50,
            "email",
            "test@test.com"
        ))->toThrow(
            new DomainNotFoundException(DomainAccount::class, "af4d8146-c829-46b6-8642-da0a0bdc2884")
        );
    });

    test("exception when unable to register the transaction", function () {
        $transactionRepository = mock(TransactionRepositoryInterface::class);
        mockTimes($transactionRepository, "create");

        $mockEventManager = mock(EventManagerInterface::class);

        $mockAccount = mock(DomainAccount::class);
        mockTimes($mockAccount, 'toArray', []);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, "find", $mockAccount);

        $mockPixKeyRepositoryInterface = mock(PixKeyRepositoryInterface::class);
        mockTimes($mockPixKeyRepositoryInterface, 'find');

        $useCase = new DebitUseCase(
            transactionRepository: $transactionRepository,
            accountRepository: $accountRepository,
            pixKeyRepository: $mockPixKeyRepositoryInterface,
            eventManager: $mockEventManager,
        );

        expect(
            fn() => $useCase->exec(
                "af4d8146-c829-46b6-8642-da0a0bdc2884",
                "testing",
                50,
                "email",
                "test@test.com"
            )
        )->toThrow(
            new UseCaseException("We were unable to register this transaction in our database")
        );
    });
});
