<?php

namespace App\Domain\Order\Entities;
use App\Domain\Order\Enums\OrderStatus;

final class Order
{
    /**
     * @param OrderItem[] $items
     */
    public function __construct(
        public array $items,
        public OrderStatus $status
    ) {
    }

    public function total(): float
    {
        return array_reduce(
            $this->items,
            fn (float $sum, OrderItem $item) => $sum + $item->subtotal(),
            0.0
        );
    }

    public function canBeUpdated(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    public function canBePaid(): bool
    {
        return $this->status === OrderStatus::CONFIRMED;
    }

}