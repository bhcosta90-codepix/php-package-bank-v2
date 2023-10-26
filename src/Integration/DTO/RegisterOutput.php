<?php

declare(strict_types=1);

namespace CodePix\Bank\Integration\DTO;

class RegisterOutput
{
    public function __construct(public string $key)
    {
    }
}