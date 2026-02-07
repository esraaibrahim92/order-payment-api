<?php

namespace App\Domain\Payment\Repositories;

use App\Domain\Payment\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function create(Payment $payment): Payment;

    /** @return Payment[] */
    public function list(?int $orderId = null): array;
    public function hasSuccessfulPayment(int $orderId): bool;
}
