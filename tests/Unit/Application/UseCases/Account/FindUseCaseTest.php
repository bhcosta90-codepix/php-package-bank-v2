<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\UseCases\Account\FindUseCase;
use CodePix\Bank\Domain\DomainAccount;

use function Tests\mockTimes;

describe("FindUseCase Unit Test", function () {
    test("get pix", function () {
        $mockDomainAccount = mock(DomainAccount::class);

        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find', $mockDomainAccount);

        $useCase = new FindUseCase(accountRepository: $accountRepository);
        $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception when do not exist a pix", function () {
        $accountRepository = mock(AccountRepositoryInterface::class);
        mockTimes($accountRepository, 'find');

        $useCase = new FindUseCase(accountRepository: $accountRepository);
        expect(fn() => $useCase->exec('7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainAccount::class, "7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });
});