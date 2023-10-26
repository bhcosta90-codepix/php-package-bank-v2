<?php

declare(strict_types=1);

namespace CodePix\System\Domain;

use CodePix\System\Domain\Enum\EnumPixType;
use CodePix\System\Domain\Enum\EnumTransactionStatus;
use CodePix\System\Domain\Events\EventTransactionCompleted;
use CodePix\System\Domain\Events\EventTransactionConfirmed;
use CodePix\System\Domain\Events\EventTransactionCreating;
use CodePix\System\Domain\Events\EventTransactionError;
use Costa\Entity\Data;
use Costa\Entity\Exceptions\EntityException;
use Costa\Entity\ValueObject\Uuid;

class DomainTransaction extends Data
{
    protected EnumTransactionStatus $status = EnumTransactionStatus::OPEN;

    protected ?string $cancelDescription = null;

    public function __construct(
        protected Uuid $bank,
        protected Uuid $reference,
        protected string $description,
        protected float $value,
        protected EnumPixType $kind,
        protected string $key,
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
        $this->addEvent(new EventTransactionError($this->bank, $this->id()));
        return $this;
    }

    /**
     * @throws EntityException
     */
    public function confirmed(): self
    {
        if ($this->status === EnumTransactionStatus::PENDING) {
            $this->status = EnumTransactionStatus::CONFIRMED;
            $this->addEvent(new EventTransactionConfirmed($this->bank, $this->id()));
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
            $this->addEvent(new EventTransactionCompleted($this->bank, $this->id()));
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