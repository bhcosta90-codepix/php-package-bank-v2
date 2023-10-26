<?php

declare(strict_types=1);

use CodePix\System\Domain\Events\EventTransactionConfirmed;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

describe("EventTransactionConfirmed Unit Test", function () {
    test("payload", function () {
        $event = new EventTransactionConfirmed($id = Uuid::make(), "test");
        assertEquals([
            'bank' => $id,
            'id' => 'test',
        ], $event->payload());
    });
});