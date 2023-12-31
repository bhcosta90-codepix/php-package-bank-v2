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

        if ($this->type == EnumTransactionType::DEBIT) {
            $this->addEvent(new EventTransactionCreating($this));
        }

        return $this;
    }

    public function error(string $message): self
    {
        $this->cancelDescription = $message;
        $this->status = EnumTransactionStatus::ERROR;
        return $this;
    }

    /**
     * @throws EntityException
     */
    public function confirmed(): self
    {
        if ($this->status === EnumTransactionStatus::PENDING || ($this->status === EnumTransactionStatus::OPEN && $this->type == EnumTransactionType::CREDIT)) {
            $this->status = EnumTransactionStatus::CONFIRMED;

            if ($this->type == EnumTransactionType::CREDIT) {
                $this->addEvent(new EventTransactionConfirmed((string)($this->reference ?: $this->id)));
            }

            if ($this->type === EnumTransactionType::CREDIT) {
                $this->account->credit($this->value);
            }

            return $this;
        }

        throw new EntityException('Only pending transaction can be confirmed');
    }

    /**
     * @throws EntityException
     */
    public function completed(): self
    {
        if ($this->status === EnumTransactionStatus::CONFIRMED || ($this->status === EnumTransactionStatus::PENDING && $this->type == EnumTransactionType::DEBIT)) {
            $this->status = EnumTransactionStatus::COMPLETED;

            if ($this->type === EnumTransactionType::DEBIT) {
                $this->addEvent(new EventTransactionCompleted((string)($this->reference ?: $this->id)));
                $this->account->debit($this->value);
            }

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
