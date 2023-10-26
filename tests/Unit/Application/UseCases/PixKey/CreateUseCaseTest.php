<?php

declare(strict_types=1);

use BRCas\CA\Exceptions\UseCaseException;
use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Application\UseCases\PixKey\CreateUseCase;
use CodePix\System\Domain\DomainPixKey;
use Costa\Entity\Exceptions\EntityException;

use function PHPUnit\Framework\assertEquals;
use function Tests\dataDomainPixKey;
use function Tests\mockTimes;

describe("CreateUseCase Unit Test", function () {
    test("create a new entity", function () {
        $mockDomainPixKey = mock(DomainPixKey::class, dataDomainPixKey());

        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find');
        mockTimes($pixKeyRepository, 'create', $mockDomainPixKey);

        $useCase = new CreateUseCase(pixKeyRepository: $pixKeyRepository);
        $response = $useCase->exec('id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60');

        assertEquals($mockDomainPixKey, $response);
    });

    test("exception when to register a pix that already exists", function () {
        $mockDomainPixKey = mock(DomainPixKey::class, dataDomainPixKey());

        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find', $mockDomainPixKey);

        $useCase = new CreateUseCase(pixKeyRepository: $pixKeyRepository);
        expect(fn() => $useCase->exec('id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new EntityException("This pix is already registered in our database")
        );
    });

    test("exception when register a new pix", function () {
        $pixKeyRepository = mock(PixKeyRepositoryInterface::class);
        mockTimes($pixKeyRepository, 'find');
        mockTimes($pixKeyRepository, 'create');

        $useCase = new CreateUseCase(pixKeyRepository: $pixKeyRepository);
        expect(fn() => $useCase->exec('id', '7b9ad99b-7c44-461b-a682-b2e87e9c3c60'))->toThrow(
            new UseCaseException("We were unable to register this pix in our database")
        );
    });
});