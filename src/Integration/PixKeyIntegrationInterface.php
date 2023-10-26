<?php

declare(strict_types=1);

namespace CodePix\Bank\Integration;

use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Integration\DTO\RegisterOutput;

interface PixKeyIntegrationInterface
{
    public function register(EnumPixType $kind, ?string $key): ?RegisterOutput;
}