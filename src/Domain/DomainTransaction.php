<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain;

use CodePix\Bank\Domain\Enum\EnumPixType;
use CodePix\Bank\Domain\Enum\EnumTransactionStatus;
use CodePix\Bank\Domain\Enum\EnumTransactionType;
use CodePix\Bank\Domain\Events\EventTransactionCompleted;
use CodePix\Bank\Domain\Events\EventTransactionConfirmed;
use CodePix\Bank\Domain\Events\EventTransactionCreating;
use CodePix\Bank\Domain\Events\EventTransactionError;
use Costa\Entity\Data;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\ValueObject\Uuid;

class DomainTransaction extends Data
{
    protected EnumTransactionStatus $status = EnumTransactionStatus::OPEN;

    protected ?string $cancelDescription = null;

    public function __construct(
        protected DomainAccount $account,
        protected ?Uuid $reference,
        protected string $description,
        protected float $value,
        protected EnumPixType $kind,
        protected string $key,
        protected EnumTransactionType $type,
    ) {
        parent::__construct();
    }

    public function pending(): self
    {
        $this->status = EnumTransactionStatus::PENDING;
        $this->addEvent(new EventTransactionCreating($this));
        return $this;
    }

    public function error(string $message): self
    {
        $this->cancelDescription = $message;
        $this->status = EnumTransactionStatus::ERROR;
        $this->addEvent(new EventTransactionError($this->id()));
        return $this;
    }

    /**
     * @throws EntityException
     */
    public function confirmed(): self
    {
        if ($this->status === EnumTransactionStatus::PENDING) {
            $this->status = EnumTransactionStatus::CONFIRMED;
            $this->addEvent(new EventTransactionConfirmed($this->id()));
            return $this;
        }

        throw new EntityException('Only pending transaction can be confirmed');
    }

    /**
     * @throws EntityException
     */
    public function completed(): self
    {
        if ($this->status === EnumTransactionStatus::CONFIRMED) {
            $this->status = EnumTransactionStatus::COMPLETED;
            $this->addEvent(new EventTransactionCompleted($this->id()));
            return $this;
        }

        throw new EntityException('Only confirmed transactions can be completed');
    }

    protected function rules(): array
    {
        return [
            'value' => 'numeric|min:0.01',
            'description' => 'min:3',
        ];
    }
}