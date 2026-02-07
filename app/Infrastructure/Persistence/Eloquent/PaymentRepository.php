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

    public function hasSuccessfulPayment(int $orderId): bool
    {
        return Payment::where('order_id', $orderId)
            ->where('status', PaymentStatus::SUCCESSFUL->value)
            ->exists();
    }

    public function paginate(?int $orderId, int $perPage): array
    {
        $query = Payment::query();

        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        $paginator = $query->paginate($perPage);

        return [
            'data' => $paginator->getCollection()->map(
                fn ($p) => new DomainPayment(
                    id: $p->id,
                    orderId: $p->order_id,
                    status: PaymentStatus::from($p->status),
                    method: $p->method
                )
            )->toArray(),

            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ];
    }

}
