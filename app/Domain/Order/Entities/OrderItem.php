<?php

namespace App\Domain\Order\Entities;

class OrderItem
{
    public function __construct(
        public ?int $id,
        public string $productName,
        public int $quantity,
        public float $price
    ) {
    }

    public function subtotal(): float
    {
        return $this->quantity * $this->price;
    }
}