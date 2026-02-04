<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Order\Entities\Order as DomainOrder;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;

final class OrderRepository implements OrderRepositoryInterface
{
    public function find(int $id): DomainOrder
    {
        $order = Order::findOrFail($id);

        return new DomainOrder(
            $order->id,
            (float) $order->total,
            OrderStatus::from($order->status)
        );
    }

    public function create(array $items): DomainOrder
    {
        $total = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            'total' => $total,
            'status' => OrderStatus::PENDING->value,
        ]);

        return new DomainOrder(
            $order->id,
            $order->total,
            OrderStatus::PENDING
        );
    }
}
