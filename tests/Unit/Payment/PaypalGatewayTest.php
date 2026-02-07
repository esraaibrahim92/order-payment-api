<?php

namespace Tests\Unit\Payment;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Enums\OrderStatus;
use App\Infrastructure\PaymentGateways\PaypalGateway;
use PHPUnit\Framework\TestCase;

class PaypalGatewayTest extends TestCase
{
    public function test_paypal_payment_fails(): void
    {
        $gateway = new PaypalGateway();

        $order = new Order(
            id: 1,
            items: [],
            status: OrderStatus::CONFIRMED
        );

        $this->assertFalse($gateway->pay($order));
    }
}
