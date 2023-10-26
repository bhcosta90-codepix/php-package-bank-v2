<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Application\UseCases\PixKey\CreateUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Integration\DTO\RegisterOutput;
use CodePix\Bank\Integration\PixKeyIntegrationInterface;
use Costa\Entity\Exceptions\EntityException;

use function PHPUnit\Framework\assertEquals;
use function Tests\dataDomainAccount;
use function Tests\dataDomainPixKey;
use function Tests\mockTimes;

describe("CreateUseCase Unit Test", function () {
    test("create a new entity", function () {
        $mockDomainPixKey = mock(DomainPixKey::class, dataDomainPixKey());

        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find');
        mockTimes($pixKeyRepository, 'create', $mockDomainPixKey);

        $pixKeyIntegration = mock(PixKeyIntegrationInterface::class);
        mockTimes($pixKeyIntegration, 'register', new RegisterOutput('testing'));

        $mockAccount = mock(DomainAccount::class, dataDomainAccount());
        mockTimes($mockAccount, 'toArray');

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find', $mockAccount);

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            pixKeyIntegration: $pixKeyIntegration,
            accountRepository: $accountRepository
        );
        $response = $useCase->exec(
            '2896e395-d646-4828-a014-1ec625243dc7',
            'id',
            '7b9ad99b-7c44-461b-a682-b2e87e9c3c60'
        );

        assertEquals($mockDomainPixKey, $response);
    });

    test("exception when to register a pix that already exists", function () {
        $mockDomainPixKey = mock(DomainPixKey::class, dataDomainPixKey());

        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find', $mockDomainPixKey);

        $pixKeyIntegration = mock(PixKeyIntegrationInterface::class);
        mockTimes($pixKeyIntegration, 'register', new RegisterOutput('testing'));

        $mockAccount = mock(DomainAccount::class, dataDomainAccount());
        mockTimes($mockAccount, 'toArray');

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find', $mockAccount);

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            pixKeyIntegration: $pixKeyIntegration,
            accountRepository: $accountRepository
        );
        expect(
            fn() => $useCase->exec('2896e395-d646-4828-a014-1ec625243dc7', 'id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60')
        )->toThrow(
            new EntityException("This pix is already registered in our database")
        );
    });

    test("exception when register a new pix", function () {
        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find');
        mockTimes($pixKeyRepository, 'create');

        $pixKeyIntegration = mock(PixKeyIntegrationInterface::class);
        mockTimes($pixKeyIntegration, 'register', new RegisterOutput('testing'));

        $mockAccount = mock(DomainAccount::class, dataDomainAccount());
        mockTimes($mockAccount, 'toArray');

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find', $mockAccount);

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            pixKeyIntegration: $pixKeyIntegration,
            accountRepository: $accountRepository
        );

        expect(
            fn() => $useCase->exec('2896e395-d646-4828-a014-1ec625243dc7', 'id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60')
        )->toThrow(
            new UseCaseException("We were unable to register this pix in our database")
        );
    });

    test("Exception when you cannot integrate with the central bank", function () {
        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);

        $pixKeyIntegration = mock(PixKeyIntegrationInterface::class);
        mockTimes($pixKeyIntegration, 'register');

        $mockDomainAccount = mock(DomainAccount::class);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find', $mockDomainAccount);

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            pixKeyIntegration: $pixKeyIntegration,
            accountRepository: $accountRepository
        );
        expect(
            fn() => $useCase->exec('2896e395-d646-4828-a014-1ec625243dc7', 'id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60')
        )->toThrow(
            new UseCaseException("The integration with PIX went wrong")
        );
    });

    test("exception when do not exist account", function () {
        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find');

        $useCase = new CreateUseCase(
            pixKeyRepository: mock(PixKeyRepositoryInterface::class),
            pixKeyIntegration: mock(PixKeyIntegrationInterface::class),
            accountRepository: $accountRepository
        );

        expect(
            fn() => $useCase->exec('2896e395-d646-4828-a014-1ec625243dc7', 'id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60')
        )->toThrow(new DomainNotFoundException(DomainAccount::class, '2896e395-d646-4828-a014-1ec625243dc7'));
    });
});