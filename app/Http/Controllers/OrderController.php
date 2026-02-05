<?php

namespace App\Http\Controllers;

use App\Application\Order\CreateOrderUseCase;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\JsonResponse;
use App\Application\Order\UpdateOrderUseCase;
use App\Http\Requests\UpdateOrderRequest;

final class OrderController extends Controller
{
    public function store(
        CreateOrderRequest $request,
        CreateOrderUseCase $useCase
    ): JsonResponse {
        $order = $useCase->execute(
            $request->input('customer'),
            $request->input('items')
        );

        return response()->json([
            'message' => 'Order created successfully',
            'total' => $order->total(),
            'status' => $order->status->value,
        ], 201);
    }

    public function update(
        int $orderId,
        UpdateOrderRequest $request,
        UpdateOrderUseCase $useCase
    ): JsonResponse {
        $order = $useCase->execute(
            $orderId,
            $request->input('customer'),
            $request->input('items')
        );

        return response()->json([
            'message' => 'Order updated successfully',
            'total'   => $order->total(),
            'status'  => $order->status->value,
        ]);
    }
}
