<?php

declare(strict_types=1);

namespace CodePix\System\Domain;

use CodePix\System\Domain\Enum\EnumPixType;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class DomainPixKey extends Data
{
    public function __construct(
        protected EnumPixType $kind,
        protected ?string $key,
    ) {
        if ($this->kind == EnumPixType::ID && empty($this->key)) {
            $this->key = (string)Uuid::make();
        }

        parent::__construct();
    }

    protected function rules(): array
    {
        return [
            'key' => 'required',
        ];
    }
}