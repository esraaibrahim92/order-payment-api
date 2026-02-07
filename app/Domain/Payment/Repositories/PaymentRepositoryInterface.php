<?php

namespace App\Domain\Payment\Repositories;

use App\Domain\Payment\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function create(Payment $payment): Payment;
    public function hasSuccessfulPayment(int $orderId): bool;
    public function paginate(?int $orderId, int $perPage): array;
}
