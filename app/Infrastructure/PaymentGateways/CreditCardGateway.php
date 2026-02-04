<?php

namespace App\Infrastructure\PaymentGateways;

use App\Domain\Order\Entities\Order;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;

final class CreditCardGateway implements PaymentGatewayInterface
{
    public function pay(Order $order): bool
    {
        return true; // simulate success
    }
}
