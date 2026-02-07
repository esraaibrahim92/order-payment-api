<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use RuntimeException;

class DeleteOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(int $orderId): void
    {
        if ($this->orders->hasPayments($orderId)) {
            throw new RuntimeException(
                'Order cannot be deleted because payments are associated.'
            );
        }

        $this->orders->delete($orderId);
    }
}
