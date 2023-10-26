<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCases\Transaction;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Exceptions\DomainNotFoundException;
use BRCas\CA\Exceptions\UseCaseException;
use CodePix\System\Application\Repository\PixKeyRepositoryInterface;
use CodePix\System\Application\Repository\TransactionRepositoryInterface;
use CodePix\System\Domain\DomainTransaction;
use CodePix\System\Domain\Enum\EnumPixType;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;

class CreateUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
        protected EventManagerInterface $eventManager,
    ) {
        //
    }

    /**
     * @throws UseCaseException
     * @throws NotificationException
     */
    public function exec(
        string $bank,
        string $id,
        string $description,
        float $value,
        string $kind,
        string $key
    ): DomainTransaction {
        $kind = EnumPixType::from($kind);

        $response = new DomainTransaction(
            bank: new Uuid($bank),
            reference: new Uuid($id),
            description: $description,
            value: $value,
            kind: $kind,
            key: $key,
        );

        if (!$this->pixKeyRepository->find($kind, $key)) {
            $response->error("Pix not found");
        } else {
            $response->pending();
        }

        if ($response = $this->transactionRepository->create($response)) {
            $this->eventManager->dispatch($response->getEvents());
            return $response;
        }

        throw new UseCaseException(
            "We were unable to register this transaction in our database"
        );
    }
}