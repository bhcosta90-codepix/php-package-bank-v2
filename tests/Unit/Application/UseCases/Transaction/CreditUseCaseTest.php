<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\UseCases\Transaction\CreditUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\DomainTransaction;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function Tests\arrayDomainPixKey;
use function Tests\mockTimes;

describe("CreditUseCase Unit Test", function () {
    test("create a new entity", function () {
        $mockDomainTransaction = mock(DomainTransaction::class);
        mockTimes($mockDomainTransaction, 'getEvents', []);

        $transactionRepository = mock(TransactionRepository::class);
        mockTimes($transactionRepository, 'create', $mockDomainTransaction);

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find', new DomainPixKey(...arrayDomainPixKey()));

        $eventManager = mock(EventManagerInterface::class);
        mockTimes($eventManager, 'dispatch');

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            eventManager: $eventManager,
        );

        $useCase->exec(
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

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            eventManager: $eventManager,
        );

        expect(fn() => $useCase->exec(
            description: 'testing',
            value: 50,
            kind: 'email',
            key: 'test@test.com'
        ))->toThrow(new DomainNotFoundException(DomainAccount::class, "test@test.com and kind: email"));
    });

    test("exception when unable to register the transaction", function () {
        $transactionRepository = mock(TransactionRepository::class);
        mockTimes($transactionRepository, 'create');

        $pixKeyRepository = mock(PixKeyRepository::class);
        mockTimes($pixKeyRepository, 'find', new DomainPixKey(...arrayDomainPixKey()));

        $eventManager = mock(EventManagerInterface::class);

        $useCase = new CreditUseCase(
            transactionRepository: $transactionRepository,
            pixKeyRepository: $pixKeyRepository,
            eventManager: $eventManager,
        );

        expect(
            fn() => $useCase->exec(
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