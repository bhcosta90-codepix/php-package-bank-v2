<?php

declare(strict_types=1);

use CodePix\Bank\Domain\Events\EventTransactionError;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionError Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionError($id = Uuid::make(), "test");
        assertEquals([
            'bank' => $id,
            'id' => 'test',
        ], $event->payload());
    });
});