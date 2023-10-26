<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\DomainNotFoundException;
use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Application\UseCases\PixKey\FindUseCase;
use CodePix\System\Domain\DomainPixKey;

use function Tests\dataDomainPixKey;
use function Tests\mockTimes;

describe("FindUseCase Unit Test", function () {
    test("get pix", function () {
        $mockDomainPixKey = mock(DomainPixKey::class, dataDomainPixKey());

        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find', $mockDomainPixKey);

        $useCase = new FindUseCase(pixKeyRepository: $pixKeyRepository);
        $useCase->exec('id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60');
    });

    test("exception when do not exist a pix", function () {
        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find');

        $useCase = new FindUseCase(pixKeyRepository: $pixKeyRepository);
        expect(fn() => $useCase->exec('id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new DomainNotFoundException(DomainPixKey::class, "kind: id and key: 7b9ad99b-7c44-461b-a682-b2e87e9c3c60")
        );
    });
});