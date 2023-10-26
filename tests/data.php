<?php

declare(strict_types=1);

namespace Tests;

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use Costa\Entity\ValueObject\Uuid;
use Mockery\MockInterface;

function mockTimes(MockInterface $mock, string $action, $response = null, $times = 1): void
{
    if ($response) {
        $mock->shouldReceive($action)->times($times)->andReturn($response);
    } else {
        $mock->shouldReceive($action)->times($times);
    }
}

function arrayDomainTransaction($type = EnumTransactionType::CREDIT): array
{
    return [
        "account" => new DomainAccount(...arrayDomainAccount()),
        "reference" => Uuid::make(),
        "description" => 'testing',
        "value" => 50,
        "kind" => EnumPixType::EMAIL,
        "key" => "test@test.com",
        'type' => $type,
    ];
}

function arrayDomainPixKey(): array
{
    $mock = mock(DomainAccount::class);
    $mock->shouldReceive('toArray');
    $mock->shouldReceive('credit');
    $mock->shouldReceive('debit');

    return [
        'account' => $mock,
        "kind" => EnumPixType::EMAIL,
        "key" => 'test@test.com',
    ];
}

function arrayDomainAccount(): array
{
    return [
        'name' => 'testing',
    ];
}

//function dataMock(): array
//{
//    return [
//        'id' => (string)Uuid::make(),
//        'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
//        'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
//    ];
//}