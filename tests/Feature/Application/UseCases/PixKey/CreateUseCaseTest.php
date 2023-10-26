<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\PixKey\CreateUseCase;
use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use Tests\Stubs\PixKeyIntegration;
use Tests\Stubs\Repository\AccountRepository;
use Tests\Stubs\Repository\PixKeyRepository;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;

beforeEach(function(){
    $this->account = new DomainAccount(name: 'testing');

    $this->accountRepository = new AccountRepository();
    $this->accountRepository->create($this->account);
});

describe("DebitUseCase Feature Test", function () {
    test("create with data", function () {
        $useCase = new CreateUseCase(
            pixKeyRepository: new PixKeyRepository(),
            pixKeyIntegration: new PixKeyIntegration(),
            accountRepository: $this->accountRepository
        );
        $response = $useCase->exec($this->account->id(), "email", "test@test.com");
        assertInstanceOf(DomainPixKey::class, $response);
    });

    test("create with id without key", function () {
        $useCase = new CreateUseCase(
            pixKeyRepository: new PixKeyRepository(),
            pixKeyIntegration: new PixKeyIntegration(),
            accountRepository: $this->accountRepository
        );
        $response = $useCase->exec($this->account->id(), "id", null);
        assertInstanceOf(DomainPixKey::class, $response);
        assertNotNull($response->key);
    });
});