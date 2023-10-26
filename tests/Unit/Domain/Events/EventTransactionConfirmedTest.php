<?php

declare(strict_types=1);

use CodePix\Bank\Domain\Events\EventTransactionConfirmed;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionConfirmed Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionConfirmed("test");
        assertEquals([
            'id' => 'test',
        ], $event->payload());
    });
});