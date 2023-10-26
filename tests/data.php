<?php

declare(strict_types=1);

namespace Tests;

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\Enum\EnumPixType;
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

function arrayDomainTransaction(): array
{
    return [
        "account" => new DomainAccount(...arrayDomainAccount()),
        "reference" => Uuid::make(),
        "description" => 'testing',
        "value" => 50,
        "kind" => EnumPixType::EMAIL,
        "key" => "test@test.com",
    ];
}

function arrayDomainPixKey(): array
{
    return [
        'account' => new DomainAccount(...arrayDomainAccount()),
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