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
        $useCase->exec('testing');
    });

    test("exception execute", function () {
        $mockRepository = mock(AccountRepositoryInterface::class);
        mockTimes($mockRepository, 'create');

        $useCase = new CreateUseCase($mockRepository);
        expect(fn() => $useCase->exec('testing'))->toThrow(new UseCaseException("Unable to register this account"));
    });
});