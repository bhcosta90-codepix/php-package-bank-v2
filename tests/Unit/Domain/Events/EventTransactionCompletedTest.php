<?php

declare(strict_types=1);

use CodePix\Bank\Domain\Events\EventTransactionCompleted;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionCompleted Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionCompleted("test");
        assertEquals([
            'id' => 'test',
        ], $event->payload());
    });
});