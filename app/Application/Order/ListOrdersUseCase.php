<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use RuntimeException;

final class ListOrdersUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(?string $status = null): array
    {
        return $this->orders->list($status);
    }
}
