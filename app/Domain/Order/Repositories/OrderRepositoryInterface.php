<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Entities\Order;

interface OrderRepositoryInterface
{
    public function find(int $orderId): Order;
    public function save(Order $order, array $customer): Order;
    public function update(int $orderId, Order $order, array $customer): Order;
    public function delete(int $orderId): void;
    public function hasPayments(int $orderId): bool;
    public function paginate(?string $status, int $perPage): array;
}