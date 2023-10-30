<?php

declare(strict_types=1);

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\ValueObject\Document;
use Costa\Entity\Exceptions\NotificationException;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

describe("DomainAccount Unit Test", function () {
    test("creating a new account", function () {
        $entity = new DomainAccount(name: 'testing', document: $document = mock(Document::class));
        assertInstanceOf(DomainAccount::class, $entity);

        assertEquals([
            'balance' => 0,
            'name' => 'testing',
            'id' => $entity->id(),
            'document' => $document,
            'created_at' => $entity->createdAt(),
            'updated_at' => $entity->updatedAt(),
        ], $entity->toArray());

        $entity = DomainAccount::make(name: 'testing', document: mock(Document::class), balance: 50);
        assertEquals($entity->balance, 50);

        $entity->credit(10);
        assertEquals($entity->balance, 60);

        $entity->debit(20);
        assertEquals($entity->balance, 40);
    });

    test("validating a account", function () {
        expect(fn() => new DomainAccount(name: 'te', document: mock(Document::class)))->toThrow(NotificationException::class);
    });
});