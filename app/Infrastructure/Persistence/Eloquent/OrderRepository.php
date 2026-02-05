<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Order\Entities\Order as DomainOrder;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;

final class OrderRepository implements OrderRepositoryInterface
{
    public function save(DomainOrder $order, array $customer): DomainOrder
    {
        $model = Order::create([
            'customer_name' => $customer['name'],
            'customer_email' => $customer['email'],
            'total' => $order->total(),
            'status' => $order->status->value,
        ]);

        foreach ($order->items as $item) {
            OrderItem::create([
                'order_id'    => $model->id,
                'product_name'=> $item->productName,
                'quantity'    => $item->quantity,
                'price'       => $item->price,
            ]);
        }

        return $order;
    }
}
