<?php

declare(strict_types=1);

use CodePix\Bank\Domain\Events\EventTransactionCompleted;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionCompleted Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionCompleted($id = Uuid::make(), "test");
        assertEquals([
            'bank' => $id,
            'id' => 'test',
        ], $event->payload());
    });
});