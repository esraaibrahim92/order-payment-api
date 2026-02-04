<?php

namespace App\Domain\Payment\Repositories;

use App\Domain\Order\Entities\Order;

interface PaymentRepositoryInterface
{
    public function create(Order $order, bool $success): void;
}