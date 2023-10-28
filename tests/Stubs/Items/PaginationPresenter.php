<?php

declare(strict_types=1);

namespace Tests\Stubs\Items;

use BRCas\CA\Contracts\Items\PaginationInterface;
use Costa\Entity\Data;

class PaginationPresenter implements PaginationInterface
{
    /**
     * @param Data[] $items
     */
    protected array $items = [];

    public function __construct(array $items)
    {
        foreach ($items as $data) {
            $this->items[] = $data->toArray();
        }
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return count($this->items);
    }

    public function lastPage(): int
    {
        return 0;
    }

    public function firstPage(): int
    {
        return 0;
    }

    public function currentPage(): int
    {
        return 0;
    }

    public function perPage(): int
    {
        return 0;
    }

    public function to(): int
    {
        return 0;
    }

    public function from(): int
    {
        return 0;
    }

}