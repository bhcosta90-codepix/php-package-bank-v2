<?php

declare(strict_types=1);

use CodePix\Bank\Application\UseCases\PixKey\CreateUseCase;
use CodePix\Bank\Domain\DomainPixKey;
use Tests\Stubs\Repository\PixKeyRepository;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;

describe("CreateUseCase Feature Test", function () {
    test("create with data", function () {
        $useCase = new CreateUseCase(pixKeyRepository: new PixKeyRepository());
        $response = $useCase->exec("email", "test@test.com");
        assertInstanceOf(DomainPixKey::class, $response);
    });

    test("create with id without key", function () {
        $useCase = new CreateUseCase(pixKeyRepository: new PixKeyRepository());
        $response = $useCase->exec("id", null);
        assertInstanceOf(DomainPixKey::class, $response);
        assertNotNull($response->key);
    });
});