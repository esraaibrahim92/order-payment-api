<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Payment\Entities\Payment as DomainPayment;
use App\Domain\Payment\Enums\PaymentStatus;
use App\Domain\Payment\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;

final class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(DomainPayment $payment): DomainPayment
    {
        $model = Payment::create([
            'order_id' => $payment->orderId,
            'status'   => $payment->status->value,
            'method'   => $payment->method,
        ]);

        return new DomainPayment(
            id: $model->id,
            orderId: $model->order_id,
            status: PaymentStatus::from($model->status),
            method: $model->method
        );
    }

    public function list(?int $orderId = null): array
    {
        return Payment::when(
            $orderId,
            fn ($q) => $q->where('order_id', $orderId)
        )->get()->map(
            fn ($payment) => new DomainPayment(
                id: $payment->id,
                orderId: $payment->order_id,
                status: PaymentStatus::from($payment->status),
                method: $payment->method
            )
        )->toArray();
    }

    public function hasSuccessfulPayment(int $orderId): bool
    {
        return Payment::where('order_id', $orderId)
            ->where('status', PaymentStatus::SUCCESSFUL->value)
            ->exists();
    }
}
