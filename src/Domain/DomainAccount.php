<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain;

use Costa\Entity\Data;

class DomainAccount extends Data
{
    protected float $balance = 0;

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

    public function credit(float $value): self
    {
        $this->balance += $value;
        return $this;
    }

    public function debit(float $value): self
    {
        $this->balance -= $value;
        return $this;
    }
}