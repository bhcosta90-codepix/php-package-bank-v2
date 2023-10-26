<?php

declare(strict_types=1);

use CodePix\Bank\Domain\DomainAccount;
use CodePix\Bank\Domain\DomainPixKey;
use CodePix\Bank\Domain\DomainTransaction;
use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;
use function Tests\mockTimes;

beforeEach(function () {
    $this->account = mock(DomainAccount::class);
    $this->account->shouldReceive('toArray')->andReturn($this->accountResult = []);
});

describe("DomainTransaction Unit Tests", function () {
    test("creating a new transaction", function () {
        $entity = new DomainTransaction(
            account: $this->account,
            reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
            description: 'testing',
            value: 50,
            kind: EnumPixType::EMAIL,
            key: 'test@test.com'
        );

        assertEquals([
            'account' => $this->accountResult,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => $entity->id(),
            'created_at' => $entity->createdAt(),
            'updated_at' => $entity->updatedAt(),
            'status' => 'open',
            'cancel_description' => null,
        ], $entity->toArray());
    });

    test("making a transaction", function () {
        $entity = DomainTransaction::make([
            "account" => $this->account,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            'account' => $this->accountResult,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
            'status' => 'open',
            'cancel_description' => null,
        ], $entity->toArray());

        $entity = DomainTransaction::make([
            "account" => $this->account,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'createdAt' => '2020-01-01 00:00:00',
            'updatedAt' => '2020-01-01 00:00:00',
        ]);

        assertEquals([
            'account' => $this->accountResult,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => "email",
            "key" => 'test@test.com',
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
            'status' => 'open',
            'cancel_description' => null,
        ], $entity->toArray());

        $entity = DomainTransaction::make([
            'account' => $this->accountResult,
            "account" => $this->account,
            'reference' => '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
            'description' => 'testing',
            'value' => 50,
            "kind" => EnumPixType::EMAIL,
            "key" => 'test@test.com',
            'status' => EnumTransactionStatus::from('confirmed'),
            'id' => '4393e8bc-73f7-11ee-b962-0242ac120002',
            'createdAt' => '2020-01-01 00:00:00',
            'updatedAt' => '2020-01-01 00:00:00',
        ]);

        assertEquals('confirmed', $entity->status->value);
    });

    test("setting a error at transaction", function () {
        $entity = new DomainTransaction(
            account: $this->account,
            reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
            description: 'testing',
            value: 50,
            kind: EnumPixType::EMAIL,
            key: 'test@test.com'
        );

        $entity->error('testing');
        assertEquals('error', $entity->status->value);
        assertEquals('testing', $entity->cancelDescription);
    });

    describe("setting confirmation a transaction", function () {
        test("success", function () {
            $entity = new DomainTransaction(
                account: $this->account,
                reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                description: 'testing',
                value: 50,
                kind: EnumPixType::EMAIL,
                key: 'test@test.com'
            );
            $entity->pending()->confirmed();
            assertEquals('confirmed', $entity->status->value);
        });

        test("error", function () {
            $entity = new DomainTransaction(
                account: $this->account,
                reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                description: 'testing',
                value: 50,
                kind: EnumPixType::EMAIL,
                key: 'test@test.com'
            );
            $entity->pending()->confirmed();

            expect(fn() => $entity->confirmed())->toThrow(
                new EntityException('Only pending transaction can be confirmed')
            );
        });
    });

    describe("setting completed a transaction", function () {
        test("success", function () {
            $entity = new DomainTransaction(
                account: $this->account,
                reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                description: 'testing',
                value: 50,
                kind: EnumPixType::EMAIL,
                key: 'test@test.com'
            );
            $entity->pending()->confirmed()->completed();
            assertEquals('completed', $entity->status->value);
        });

        test("error", function () {
            $entity = new DomainTransaction(
                account: $this->account,
                reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                description: 'testing',
                value: 50,
                kind: EnumPixType::EMAIL,
                key: 'test@test.com'
            );
            expect(fn() => $entity->completed())->toThrow(
                new EntityException('Only confirmed transactions can be completed')
            );
        });
    });

    describe("validation an entity", function () {
        describe("at constructor", function () {
            test("validate property value", function () {
                expect(fn() => new DomainTransaction(
                    account: $this->account,
                    reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                    description: 'testing',
                    value: 0,
                    kind: EnumPixType::EMAIL,
                    key: 'test@test.com'
                ))->toThrow(NotificationException::class);
            });

            test("validate property description", function () {
                expect(fn() => new DomainTransaction(
                    account: $this->account,
                    reference: new Uuid('22e7e7e3-2f38-4c06-b9e7-12335b45a0db'),
                    description: 'te',
                    value: 0.01,
                    kind: EnumPixType::EMAIL,
                    key: 'test@test.com'
                ))->toThrow(NotificationException::class);
            });
        });

        describe("at make", function () {
            test("validate property value", function () {
                expect(fn() => DomainTransaction::make(
                    account: $this->account,
                    reference: '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
                    description: 'testing',
                    value: 0,
                    kind: EnumPixType::EMAIL,
                    key: 'test@test.com'
                ))->toThrow(NotificationException::class);
            });

            test("validate property description", function () {
                expect(fn() => DomainTransaction::make(
                    account: $this->account,
                    reference: '22e7e7e3-2f38-4c06-b9e7-12335b45a0db',
                    description: 'te',
                    value: 0.01,
                    kind: EnumPixType::EMAIL,
                    key: 'test@test.com'
                ))->toThrow(NotificationException::class);
            });
        });
    });
});