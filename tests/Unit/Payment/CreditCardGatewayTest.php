<?php

namespace Tests\Unit\Payment;

use App\Domain\Order\Entities\Order;
use App\Domain\Order\Enums\OrderStatus;
use App\Infrastructure\PaymentGateways\CreditCardGateway;
use PHPUnit\Framework\TestCase;

final class CreditCardGatewayTest extends TestCase
{
    public function test_credit_card_payment_succeeds(): void
    {
        $gateway = new CreditCardGateway('fake-api-key');

        $order = new Order(
            id: 1,
            items: [],
            status: OrderStatus::CONFIRMED
        );

        $this->assertTrue($gateway->pay($order));
    }
}
