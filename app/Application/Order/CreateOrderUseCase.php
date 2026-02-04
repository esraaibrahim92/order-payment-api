<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;

final class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(array $items)
    {
        return $this->orders->create($items);
    }
}
