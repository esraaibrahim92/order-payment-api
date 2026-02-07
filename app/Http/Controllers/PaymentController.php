<?php

namespace App\Http\Controllers;

use App\Application\Payment\ProcessPaymentUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\Payment\ListPaymentsUseCase;

final class PaymentController extends Controller
{
    public function index(Request $request, ListPaymentsUseCase $useCase): JsonResponse 
    {
        $payments = $useCase->execute(null, (int) $request->query('per_page', 10));
  
        return response()->json([
            'data' => array_map(fn ($payment) => [
                'id'       => $payment->id,
                'order_id' => $payment->orderId,
                'status'   => $payment->status->value,
                'method'   => $payment->method,
            ], $payments['data']),
            'meta' => $payments['meta'],
        ]);
    }

    public function orderPayments(int $orderId, Request $request, ListPaymentsUseCase $useCase): JsonResponse 
    {
        $payments = $useCase->execute($orderId, (int) $request->query('per_page', 10));

        return response()->json([
            'data' => array_map(fn ($payment) => [
                'id'       => $payment->id,
                'order_id' => $payment->orderId,
                'status'   => $payment->status->value,
                'method'   => $payment->method,
            ], $payments['data']),
            'meta' => $payments['meta'],
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
