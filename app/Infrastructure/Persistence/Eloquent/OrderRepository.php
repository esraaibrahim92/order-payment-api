<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Order\Entities\Order as DomainOrder;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Domain\Order\Entities\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

final class OrderRepository implements OrderRepositoryInterface
{
    public function find(int $orderId): DomainOrder
    {
        $orderModel = Order::with('items')->findOrFail($orderId);
        $items = $orderModel->items->map(
            fn ($item) => new OrderItem(
                $item->id,
                $item->product_name,
                $item->quantity,
                (float) $item->price
            )
        )->toArray();

        return new DomainOrder(
            id: $orderModel->id,
            items: $items,
            status: OrderStatus::from($orderModel->status)
        );
    }

    public function save(DomainOrder $order, array $customer): DomainOrder
    {
        return DB::transaction(function () use ($order, $customer) {

            $orderModel = Order::create([
                'customer_name'  => $customer['name'],
                'customer_email' => $customer['email'],
                'total'          => $order->total(),
                'status'         => $order->status->value,
            ]);
            
        $domainItems = [];

        foreach ($order->items as $item) {
            $itemModel = $orderModel->items()->create([
                'product_name' => $item->productName,
                'quantity'     => $item->quantity,
                'price'        => $item->price,
            ]);

            $domainItems[] = new OrderItem(
                id: $itemModel->id,
                productName: $item->productName,
                quantity: $item->quantity,
                price: $item->price
            );
        }

        return new DomainOrder(
            id: $orderModel->id,
            items: $domainItems,
            status: $order->status
        );
        });
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

    public function hasPayments(int $orderId): bool
    {
        return Payment::where('order_id', $orderId)->exists();
    }

    public function delete(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->items()->delete();
        $order->delete();
    }

    public function list(?string $status = null): array
    {
        $query = Order::with('items');

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($orderModel) {

            $items = $orderModel->items->map(
                fn ($item) => new OrderItem(
                    $item->id,
                    $item->product_name,
                    (int) $item->quantity,
                    (float) $item->price
                )
            )->toArray();

            return new DomainOrder(
                id: $orderModel->id,
                items: $items,
                status: OrderStatus::from($orderModel->status)
            );
        })->toArray();
    }
}
