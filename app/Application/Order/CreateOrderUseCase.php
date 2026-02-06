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
                id: null, 
                productName: $item['product_name'],
                quantity: (int) $item['quantity'],
                price: (float) $item['price']
            ),
            $items
        );

        $order = new Order(
            id: null,
            items: $orderItems,
            status: OrderStatus::PENDING
        );


        return $this->orders->save($order, $customer);
    }
}
