<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\UseCaseException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\UseCases\Account\CreateUseCase;
use CodePix\Bank\Domain\DomainAccount;

use function Tests\mockTimes;

describe("CreateUseCase Unit Test", function () {
    test("execute", function () {
        $mockRepository = mock(AccountRepositoryInterface::class);
        mockTimes($mockRepository, 'create', mock(DomainAccount::class));

        $useCase = new CreateUseCase($mockRepository);
        $useCase->exec('testing', '97.002.686/0001-91');
    });

    test("exception execute", function () {
        $mockRepository = mock(AccountRepositoryInterface::class);
        mockTimes($mockRepository, 'create');

        $useCase = new CreateUseCase($mockRepository);
        expect(fn() => $useCase->exec('testing', '97.002.686/0001-91'))->toThrow(new UseCaseException("Unable to register this account"));
    });
});