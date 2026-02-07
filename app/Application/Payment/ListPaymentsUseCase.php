<?php

namespace App\Application\Payment;

use App\Domain\Payment\Repositories\PaymentRepositoryInterface;

final class ListPaymentsUseCase
{
    public function __construct(
        private PaymentRepositoryInterface $payments
    ) {
    }

    /**
     * @return array
     */
    public function execute(?int $orderId = null): array
    {
        return $this->payments->list($orderId);
    }
}
