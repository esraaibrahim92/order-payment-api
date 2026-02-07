<?php

namespace App\Application\Order;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use RuntimeException;

class ListOrdersUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders
    ) {
    }

    public function execute(?string $status = null, int $perPage): array
    {
        return $this->orders->paginate($status, $perPage);
    }
}
