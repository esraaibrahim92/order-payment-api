<?php

namespace App\Domain\Payment\Gateways;
use App\Domain\Order\Entities\Order;

interface PaymentGatewayInterface
{
    public function pay(Order $order): bool;
}