<?php

namespace Tests\Unit\Domain\Order;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\OrderItem;
use App\Domain\Order\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function test_it_calculates_order_total_correctly(): void
    {
        $items = [
            new OrderItem(id: null, productName: 'Laptop', quantity: 1, price: 1000),
            new OrderItem(id: null, productName: 'Mouse', quantity: 2, price: 50),
        ];

        $order = new Order(
            id: null,
            items: $items,
            status: OrderStatus::PENDING
        );

        $this->assertEquals(1100, $order->total());
    }
}
