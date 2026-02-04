<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Entities\Order;

interface OrderRepositoryInterface
{
    public function find(int $id): Order;
    public function create(array $items): Order;
}