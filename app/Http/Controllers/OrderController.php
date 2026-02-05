<?php

namespace App\Http\Controllers;

use App\Application\Order\CreateOrderUseCase;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\JsonResponse;

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
}
