<?php

declare(strict_types=1);

use CodePix\System\Application\UseCases\Transaction\CreateUseCase;
use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;
use CodePix\System\Domain\Enum\EnumTransactionStatus;
use Tests\Stubs\EventManager;
use Tests\Stubs\Repository\PixKeyRepository;
use Tests\Stubs\Repository\TransactionRepository;

use function PHPUnit\Framework\assertEquals;

describe("CreateUseCase Unit Test", function () {
    test("create a new entity", function () {
        $pixKeyRepository = new PixKeyRepository();
        $pixKeyRepository->create(new DomainPixKey(EnumPixType::EMAIL, "test@test.com"));

        $transactionRepository = new TransactionRepository();

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            transactionRepository: $transactionRepository,
            eventManager: new EventManager(),
        );

        $response = $useCase->exec(
            "af4d8146-c829-46b6-8642-da0a0bdc2884",
            "9a439706-13ff-4a33-99ab-0bb80bb6b567",
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::PENDING);
    });

    test("exception when to pix do not exist", function () {
        $pixKeyRepository = new PixKeyRepository();
        $transactionRepository = new TransactionRepository();

        $useCase = new CreateUseCase(
            pixKeyRepository: $pixKeyRepository,
            transactionRepository: $transactionRepository,
            eventManager: new EventManager(),
        );

        $response = $useCase->exec(
            "af4d8146-c829-46b6-8642-da0a0bdc2884",
            "9a439706-13ff-4a33-99ab-0bb80bb6b567",
            "testing",
            50,
            "email",
            "test@test.com"
        );

        assertEquals($response->status, EnumTransactionStatus::ERROR);
        assertEquals($response->cancelDescription, 'Pix not found');
    });
});