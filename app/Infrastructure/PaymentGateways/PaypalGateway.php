<?php

namespace App\Infrastructure\PaymentGateways;

use App\Domain\Order\Entities\Order;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;

class PaypalGateway implements PaymentGatewayInterface
{
    public function method(): string
    {
        return 'paypal';
    }
    public function pay(Order $order): bool
    {
        return false; // simulate failure
    }
}
