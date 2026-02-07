<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderItem;
use App\Domain\Order\Enums\OrderStatus;
use RuntimeException;

class UpdateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(
        int $orderId,
        array $customer,
        array $items
    ): Order {
        $existingOrder = $this->orders->find($orderId);

        if (! $existingOrder->canBeUpdated()) {
            throw new RuntimeException('Only pending orders can be updated.');
        }

        $orderItems = array_map(
            fn ($item) => new OrderItem(
                null,
                $item['product_name'],
                $item['quantity'],
                (float) $item['price']
            ),
            $items
        );

        $updatedOrder = new Order(
            id: $orderId,
            items: $orderItems,
            status: OrderStatus::PENDING
        );

        return $this->orders->update(
            $orderId,
            $updatedOrder,
            $customer
        );
    }
}
