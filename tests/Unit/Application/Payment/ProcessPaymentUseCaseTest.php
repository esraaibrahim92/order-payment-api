<?php

namespace Tests\Unit\Application\Payment;

use App\Application\Payment\ProcessPaymentUseCase;
use App\Application\Payment\Gateway\PaymentGatewayRegistry;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Domain\Payment\Repositories\PaymentRepositoryInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ProcessPaymentUseCaseTest extends TestCase
{
    public function test_cannot_pay_unconfirmed_order(): void
    {
        $orders = $this->createMock(OrderRepositoryInterface::class);
        $payments = $this->createMock(PaymentRepositoryInterface::class);
        $registry = $this->createMock(PaymentGatewayRegistry::class); 

        $orders->method('find')->willReturn(
            new Order(
                id: 1,
                items: [],
                status: OrderStatus::PENDING
            )
        );

        $useCase = new ProcessPaymentUseCase(
            $orders,
            $payments,
            $registry
        );

        $this->expectException(RuntimeException::class);

        $useCase->execute(1, 'credit_card');
    }
}
