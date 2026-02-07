<?php

namespace Tests\Unit\Application\Payment;

use App\Application\Payment\ProcessPaymentUseCase;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Repositories\PaymentRepositoryInterface;
use App\Domain\Payment\Gateways\PaymentGatewayInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ProcessPaymentUseCaseTest extends TestCase
{
    public function test_cannot_pay_unconfirmed_order(): void
    {
        $orders = $this->createMock(OrderRepositoryInterface::class);
        $payments = $this->createMock(PaymentRepositoryInterface::class);
        $gateway = $this->createMock(PaymentGatewayInterface::class);

        $orders->method('find')->willReturn(
            new \App\Domain\Order\Entities\Order(
                id: 1,
                items: [],
                status: OrderStatus::PENDING
            )
        );

        $useCase = new ProcessPaymentUseCase($orders, $payments, $gateway);

        $this->expectException(RuntimeException::class);

        $useCase->execute(1, 'credit_card');
    }
}
