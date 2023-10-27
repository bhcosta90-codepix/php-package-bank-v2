<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\UseCases\Transaction\CreditUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\DomainTransaction;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function Tests\arrayDomainAccount;
use function Tests\arrayDomainPixKey;
use function Tests\mockTimes;

beforeEach(function(){
    $this->mockDomainAcocunt = $this->getMockBuilder(DomainAccount::class)
        ->setConstructorArgs(arrayDomainAccount())
        ->getMock();

    $this->mockDomainPix = $this->getMockBuilder(DomainPixKey::class)
        ->onlyMethods(['__get'])
        ->disableOriginalConstructor()
        ->getMock();

    $this->mockDomainPix->method('__get')
        ->with('account')
        ->willReturn($this->mockDomainAcocunt);
});

describe("CreditUseCase Unit Test", function () {
    test("create a new entity", function () {
        $mockDomainTransaction = mock(DomainTransaction::class);
        mockTimes($mockDomainTransaction, 'getEvents', []);

        $transactionRepository = mock(TransactionRepository::class);
        mockTimes($transactionRepository, 'create', $mockDomainTransaction);

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find', $this->mockDomainPix);

        $eventManager = mock(EventManagerInterface::class);
        mockTimes($eventManager, 'dispatch');

        $accountRepository = mock(AccountRepository::class);
        mockTimes($accountRepository, 'save', $this->mockDomainPix->account);

        $databaseTransaction = mock(DatabaseTransactionInterface::class);
        mockTimes($databaseTransaction, 'commit');

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            accountRepository: $accountRepository,
            eventManager: $eventManager,
            databaseTransaction: $databaseTransaction
        );

        $useCase->exec(
            id: '30efe625-546e-4d6f-b207-2fed0db79197',
            description: 'testing',
            value: 50,
            kind: 'email',
            key: 'test@test.com'
        );
    });

    test("exception when account do not exist", function () {
        $transactionRepository = mock(TransactionRepository::class);

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find');

        $eventManager = mock(EventManagerInterface::class);

        $databaseTransaction = mock(DatabaseTransactionInterface::class);

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            accountRepository: mock(AccountRepository::class),
            eventManager: $eventManager,
            databaseTransaction: $databaseTransaction,
        );

        expect(fn() => $useCase->exec(
            id: '30efe625-546e-4d6f-b207-2fed0db79197',
            description: 'testing',
            value: 50,
            kind: 'email',
            key: 'test@test.com'
        ))->toThrow(new DomainNotFoundException(DomainAccount::class, "test@test.com and kind: email"));
    });

    test("exception commit to database transaction", function(){

        $transactionRepository = mock(TransactionRepository::class);
        mockTimes($transactionRepository, 'create', mock(DomainTransaction::class));

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find', $this->mockDomainPix);

        $mockDomainAccount = mock(DomainAccount::class);

        $accountRepository = mock(AccountRepository::class);
        mockTimes($accountRepository, 'save', $mockDomainAccount);

        $databaseTransaction = mock(DatabaseTransactionInterface::class);
        mockTimes($databaseTransaction, 'rollback');
        $databaseTransaction->shouldReceive('commit')->andThrow(new Exception());

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            accountRepository: $accountRepository,
            eventManager: mock(EventManagerInterface::class),
            databaseTransaction: $databaseTransaction
        );

        expect(fn() => $useCase->exec(
            id: '30efe625-546e-4d6f-b207-2fed0db79197',
            description: 'testing',
            value: 50,
            kind: 'email',
            key: 'test@test.com'
        ))->toThrow(new Exception());
    });

    test("exception when unable to register the transaction", function () {
        $transactionRepository = mock(TransactionRepository::class);
        mockTimes($transactionRepository, 'create');

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find', $this->mockDomainPix);

        $eventManager = mock(EventManagerInterface::class);

        $databaseTransaction = mock(DatabaseTransactionInterface::class);

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            accountRepository: mock(AccountRepository::class),
            eventManager: $eventManager,
            databaseTransaction: $databaseTransaction
        );

        expect(
            fn() => $useCase->exec(
                id: '30efe625-546e-4d6f-b207-2fed0db79197',
                description: 'testing',
                value: 50,
                kind: 'email',
                key: 'test@test.com'
            )
        )->toThrow(
            new UseCaseException("We were unable to register this transaction in our database")
        );
    });
});