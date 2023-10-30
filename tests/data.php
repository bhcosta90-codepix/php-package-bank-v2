<?php

declare(strict_types=1);

namespace Tests;

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use CodePix\Bank\ValueObject\Document;
use Costa\Entity\ValueObject\Uuid;
use Mockery\MockInterface;

function mockTimes(MockInterface $mock, string $action, $response = null, $times = 1): void
{
    if ($response !== null) {
        $mock->shouldReceive($action)->times($times)->andReturn($response);
    } else {
        $mock->shouldReceive($action)->times($times);
    }
}

function arrayDomainTransaction($type = EnumTransactionType::CREDIT, $account = []): array
{
    return [
        "account" => DomainAccount::make($account + arrayDomainAccount()),
        "reference" => Uuid::make(),
        "description" => 'testing',
        "value" => 50,
        "kind" => EnumPixType::EMAIL,
        "key" => "test@test.com",
        'type' => $type,
    ];
}

function arrayDomainPixKey(array $account = []): array
{
    return [
        'account' => DomainAccount::make($account + arrayDomainAccount()),
        "kind" => EnumPixType::EMAIL,
        "key" => 'test@test.com',
    ];
}

function arrayDomainAccount(): array
{
    return [
        'name' => 'testing',
        'document' => mock(Document::class),
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