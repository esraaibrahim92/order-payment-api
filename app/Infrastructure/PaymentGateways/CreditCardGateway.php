<?php

namespace App\Infrastructure\PaymentGateways;

use App\Domain\Order\Entities\Order;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function method(): string
    {
        return 'credit_card';
    }

    public function pay(Order $order): bool
    {
        return true; // simulate success
    }
}
