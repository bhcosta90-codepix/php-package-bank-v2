<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain;

use CodePix\Bank\ValueObject\Document;
use Costa\Entity\Data;

class DomainAccount extends Data
{
    protected float $balance = 0;

    public function __construct(
        protected string $name,
        protected Document $document,
    ) {
        parent::__construct();
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

    protected function rules(): array
    {
        return [
            'name' => 'min:3',
        ];
    }
}