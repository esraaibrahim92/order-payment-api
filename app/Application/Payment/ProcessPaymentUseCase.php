<?php

namespace App\Application\Payment;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Entities\Payment;
use App\Domain\Payment\Enums\PaymentStatus;
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

    public function execute(int $orderId, string $method): Payment
    {
        $order = $this->orders->find($orderId);

        if ($order->status !== OrderStatus::CONFIRMED) {
            throw new RuntimeException(
                'Payments can only be processed for confirmed orders.'
            );
        }

        if ($this->payments->hasSuccessfulPayment($orderId)) {
            throw new RuntimeException(
                'Order has already been paid successfully.'
            );
        }

        $success = $this->gateway->pay($order);

        $payment = new Payment(
            id: null,
            orderId: $orderId,
            status: $success
                ? PaymentStatus::SUCCESSFUL
                : PaymentStatus::FAILED,
            method: $method
        );

        return $this->payments->create($payment);
    }
}
