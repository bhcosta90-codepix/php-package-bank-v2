<?php

declare(strict_types=1);

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\Enum\EnumPixType;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function Tests\dataDomainAccount;

describe("DomainPixKey Unit Tests", function () {
    test("creating a new transaction", function () {
        $entity = new DomainPixKey(
            account: $account = new DomainAccount(...dataDomainAccount()),
            kind: EnumPixType::EMAIL,
            key: 'test@test.com',
        );

        assertEquals([
            'account' => $account->toArray(),
            'kind' => 'email',
            'key' => 'test@test.com',
            'id' => $entity->id(),
            'created_at' => $entity->createdAt(),
            'updated_at' => $entity->updatedAt(),
        ], $entity->toArray());

        $entity = new DomainPixKey(
            account: new DomainAccount(...dataDomainAccount()),
            kind: EnumPixType::ID,
            key: '4393e8bc-73f7-11ee-b962-0242ac120002',
        );

        assertEquals('4393e8bc-73f7-11ee-b962-0242ac120002', $entity->key);

        $entity = DomainPixKey::make(
            account: new DomainAccount(...dataDomainAccount()),
            kind: EnumPixType::ID,
            key: '4393e8bc-73f7-11ee-b962-0242ac120002',
        );

        assertEquals('4393e8bc-73f7-11ee-b962-0242ac120002', $entity->key);
    });

    test("making a transaction", function () {
        $entity = DomainPixKey::make([
            "account" => $account = new DomainAccount(...dataDomainAccount()),
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            "account" => $account->toArray(),
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ], $entity->toArray());

        $entity = DomainPixKey::make([
            "account" => $account = new DomainAccount(...dataDomainAccount()),
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'createdAt' => '2020-01-01 00:00:00',
            'updatedAt' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            "account" => $account->toArray(),
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ], $entity->toArray());
    });
});