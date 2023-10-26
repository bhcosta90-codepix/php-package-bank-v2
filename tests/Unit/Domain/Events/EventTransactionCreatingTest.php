<?php

declare(strict_types=1);

use CodePix\System\Domain\DomainTransaction;
use CodePix\System\Domain\Events\EventTransactionCreating;

use function PHPUnit\Framework\assertEquals;
use function Tests\dataDomainTransaction;
use function Tests\mockTimes;

describe("EventTransactionCreating Unit Test", function () {
    test("payload", function () {
        $mock = mock(DomainTransaction::class, dataDomainTransaction());
        mockTimes($mock, 'toArray', $result = ['resa' => '123']);

        $event = new EventTransactionCreating($mock);

        assertEquals($result, $event->payload());
    });
});