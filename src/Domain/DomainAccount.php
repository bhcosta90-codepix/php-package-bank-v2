<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain;

use Costa\Entity\Data;

class DomainAccount extends Data
{
    public function __construct(
        protected string $name,
    ) {
        parent::__construct();
    }

    protected function rules(): array
    {
        return [
            'name' => 'min:3',
        ];
    }
}