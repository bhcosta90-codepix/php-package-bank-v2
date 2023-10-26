<?php

declare(strict_types=1);

namespace Tests;

use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;
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

function dataDomainTransaction(): array
{
    return [
        "bank" => Uuid::make(),
        "reference" => Uuid::make(),
        "description" => 'testing',
        "value" => 50,
        "kind" => EnumPixType::EMAIL,
        "key" => "test@test.com",
    ];
}

function dataDomainPixKey(): array
{
    return [
        "kind" => EnumPixType::EMAIL,
        "key" => 'test@test.com',
    ];
}

function dataMock(): array
{
    return [
        'id' => (string)Uuid::make(),
        'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
        'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
    ];
}