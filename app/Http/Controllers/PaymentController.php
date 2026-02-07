<?php

namespace App\Http\Controllers;

use App\Application\Payment\ProcessPaymentUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\Payment\ListPaymentsUseCase;
use App\Application\Payment\Gateway\PaymentGatewayRegistry;

class PaymentController extends Controller
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

    public function store(Request $request, ProcessPaymentUseCase $useCase, PaymentGatewayRegistry $registry): JsonResponse 
    {
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'payment_method' => ['required', 'string'],
        ]);

        if (! in_array($request->payment_method, $registry->supportedMethods(), true)) {
            return response()->json([
                'message' => 'Unsupported payment method'
            ], 422);
        }

        $payment = $useCase->execute(
            $request->order_id,
            $request->payment_method
        );

        return response()->json([
            'status' => $payment->status->value,
        ], 201);
    }
}
