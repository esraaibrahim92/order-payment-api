<?php

namespace App\Infrastructure\PaymentGateways;

use App\Domain\Order\Entities\Order;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;

final class PaypalGateway implements PaymentGatewayInterface
{
    public function pay(Order $order): bool
    {
        return false; // simulate failure
    }
}
