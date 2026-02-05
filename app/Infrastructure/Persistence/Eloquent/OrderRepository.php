<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Order\Entities\Order as DomainOrder;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Domain\Order\Entities\OrderItem;
use Illuminate\Support\Facades\DB;

final class OrderRepository implements OrderRepositoryInterface
{
    public function find(int $orderId): DomainOrder
    {
        $orderModel = Order::with('items')->findOrFail($orderId);
        $items = $orderModel->items->map(
            fn ($item) => new OrderItem(
                $item->product_name,
                $item->quantity,
                (float) $item->price
            )
        )->toArray();

        return new DomainOrder(
            items: $items,
            status: OrderStatus::from($orderModel->status)
        );
    }

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

    public function update(int $orderId, DomainOrder $order, array $customer): DomainOrder 
    {
        return DB::transaction(function () use ($orderId, $order, $customer) {

            $orderModel = Order::findOrFail($orderId);

            // 1️⃣ Update order fields
            $orderModel->update([
                'customer_name'  => $customer['name'],
                'customer_email' => $customer['email'],
                'total'          => $order->total(),
            ]);

            // 2️⃣ Replace order items
            $orderModel->items()->delete();

            foreach ($order->items as $item) {
                $orderModel->items()->create([
                    'product_name' => $item->productName,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                ]);
            }

            return $order;
        });
    }
}
