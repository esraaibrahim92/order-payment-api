<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderItem;
use App\Domain\Order\Enums\OrderStatus;

final class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(array $customer, array $items): Order
    {
        $orderItems = array_map(
            fn ($item) => new OrderItem(
                $item['product_name'],
                $item['quantity'],
                (float) $item['price']
            ),
            $items
        );

        $order = new Order(
            items: $orderItems,
            status: OrderStatus::PENDING
        );

        return $this->orders->save($order, $customer);
    }
}
