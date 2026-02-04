<?php

namespace App\Domain\Order\Entities;
use App\Domain\Order\Enums\OrderStatus;

final class Order
{
    public function __construct(
        public int $id,
        public float $total,
        public OrderStatus $status
    ) {
    }

    public function canBePaid(): bool
    {
        return $this->status === OrderStatus::CONFIRMED;
    }
}