<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\Bank\Application\Repository\AccountRepositoryInterface;
use CodePix\Bank\Application\UseCases\Account\MyPixKeyUseCase;

use CodePix\Bank\Domain\DomainAccount;

use Costa\Entity\ValueObject\Uuid;

use function Tests\mockTimes;

describe("MyPixKeyUseCase Unit Test", function () {
    test("execute", function () {

        $account = mock(DomainAccount::class);
        $account->shouldReceive('id')->andReturn((string) Uuid::class);

        $mock = mock(AccountRepositoryInterface::class);
        mockTimes($mock, 'find', $account);
        mockTimes($mock, 'myPixKeys');

        $useCase = new MyPixKeyUseCase($mock);
        $useCase->exec($account->id());
    });

    test("exception when account do not exist", function () {

        $account = mock(DomainAccount::class);
        $account->shouldReceive('id')->andReturn($id = (string) Uuid::class);

        $mock = mock(AccountRepositoryInterface::class);
        mockTimes($mock, 'find');

        $useCase = new MyPixKeyUseCase($mock);
        expect(fn() => $useCase->exec($account->id()))->toThrow(new DomainNotFoundException(DomainAccount::class, $id));
    });
});