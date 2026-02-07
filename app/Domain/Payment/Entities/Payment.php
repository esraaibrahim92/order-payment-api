<?php

namespace App\Domain\Payment\Entities;
use App\Domain\Payment\Enums\PaymentStatus;

final class Payment
{
    /**
     * @param OrderItem[] $items
     */
    public function __construct(
        public ?int $id,
        public int $orderId,
        public PaymentStatus $status,
        public string $method
    ) {
    }
}