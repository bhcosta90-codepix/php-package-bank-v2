<?php

declare(strict_types=1);

use CodePix\System\Domain\DomainPixKey;
use CodePix\System\Domain\Enum\EnumPixType;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

describe("DomainPixKey Unit Tests", function () {
    test("creating a new transaction", function () {
        $entity = new DomainPixKey(
            kind: EnumPixType::EMAIL,
            key: 'test@test.com',
        );

        assertEquals([
            'kind' => 'email',
            'key' => 'test@test.com',
            'id' => $entity->id(),
            'created_at' => $entity->createdAt(),
            'updated_at' => $entity->updatedAt(),
        ], $entity->toArray());

        $entity = new DomainPixKey(
            kind: EnumPixType::ID,
            key: '4393e8bc-73f7-11ee-b962-0242ac120002',
        );

        assertEquals('4393e8bc-73f7-11ee-b962-0242ac120002', $entity->key);

        $entity = DomainPixKey::make(
            kind: EnumPixType::ID,
            key: '4393e8bc-73f7-11ee-b962-0242ac120002',
        );

        assertEquals('4393e8bc-73f7-11ee-b962-0242ac120002', $entity->key);
    });

    test("making a transaction", function () {
        $entity = DomainPixKey::make([
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ], $entity->toArray());

        $entity = DomainPixKey::make([
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'createdAt' => '2020-01-01 00:00:00',
            'updatedAt' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ], $entity->toArray());
    });

    test("creating a new pix key with type id", function () {
        $entity = new DomainPixKey(
            kind: EnumPixType::ID,
            key: null,
        );

        assertNotNull($entity->key);
    });
});