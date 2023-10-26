<?php

declare(strict_types=1);

use CodePix\Bank\Domain\Events\EventTransactionError;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionError Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionError("test");
        assertEquals([
            'id' => 'test',
        ], $event->payload());
    });
});