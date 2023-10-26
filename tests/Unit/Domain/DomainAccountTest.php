<?php

declare(strict_types=1);

use CodePix\Bank\Domain\DomainAccount;
use Costa\Entity\Exceptions\NotificationException;

use function PHPUnit\Framework\assertInstanceOf;

describe("DomainAccount Unit Test", function () {
    test("creating a new account", function () {
        $account = new DomainAccount(name: 'testing');
        assertInstanceOf(DomainAccount::class, $account);
    });

    test("validating a account", function () {
        expect(fn() => new DomainAccount(name: 'te'))->toThrow(NotificationException::class);
    });
});