<?php

namespace App\Application\Payment;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;
use App\Domain\Payment\Repositories\PaymentRepositoryInterface;
use RuntimeException;

final class ProcessPaymentUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orders,
        private PaymentRepositoryInterface $payments,
        private PaymentGatewayInterface $gateway
    ) {
    }

    public function execute(int $orderId): void
    {
        $order = $this->orders->find($orderId);

        if (! $order->canBePaid()) {
            throw new RuntimeException('Order must be confirmed before payment.');
        }

        $success = $this->gateway->pay($order);
        $this->payments->create($order, $success);
    }
}
