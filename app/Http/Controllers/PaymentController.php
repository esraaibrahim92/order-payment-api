<?php

namespace App\Http\Controllers;

use App\Application\Payment\ProcessPaymentUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\Payment\ListPaymentsUseCase;

final class PaymentController extends Controller
{
    public function index(ListPaymentsUseCase $useCase): JsonResponse 
    {
        $payments = $useCase->execute();

        return response()->json([
            'data' => array_map(fn ($payment) => [
                'id'       => $payment->id,
                'order_id' => $payment->orderId,
                'status'   => $payment->status->value,
                'method'   => $payment->method,
            ], $payments),
        ]);
    }

    public function orderPayments(int $orderId, ListPaymentsUseCase $useCase): JsonResponse 
    {
        $payments = $useCase->execute($orderId);

        return response()->json([
            'data' => array_map(fn ($payment) => [
                'id'       => $payment->id,
                'order_id' => $payment->orderId,
                'status'   => $payment->status->value,
                'method'   => $payment->method,
            ], $payments),
        ]);
    }

    public function store(Request $request, ProcessPaymentUseCase $useCase): JsonResponse 
    {
        $payment = $useCase->execute(
            $request->order_id,
            $request->payment_method
        );

        return response()->json([
            'id'       => $payment->id,
            'order_id' => $payment->orderId,
            'status'   => $payment->status->value,
            'method'   => $payment->method,
        ], 201);
    }
}
