<?php

namespace App\Http\Controllers;

use App\Application\Order\CreateOrderUseCase;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\JsonResponse;
use App\Application\Order\UpdateOrderUseCase;
use App\Http\Requests\UpdateOrderRequest;
use App\Application\Order\DeleteOrderUseCase;
use App\Application\Order\ListOrdersUseCase;

class OrderController extends Controller
{
    public function index(Request $request, ListOrdersUseCase $useCase): JsonResponse
    {
        $orders = $useCase->execute($request->query('status'),(int) $request->query('per_page', 10));


        return response()->json([
            'data' => array_map(fn ($order) => [
                'id'     => $order->id,
                'status' => $order->status->value,
                'total'  => $order->total(),
                'items'  => array_map(fn ($item) => [
                    'id'           => $item->id,
                    'product_name' => $item->productName,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                ], $order->items),
            ], $orders['data']), 

            'meta' => $orders['meta'],
        ]);
    }

    public function store(CreateOrderRequest $request, CreateOrderUseCase $useCase): JsonResponse
    {
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

    public function update(int $orderId, UpdateOrderRequest $request, UpdateOrderUseCase $useCase): JsonResponse 
    {
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

    public function destroy(int $orderId, DeleteOrderUseCase $useCase): JsonResponse
    {
        $useCase->execute($orderId);

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }


}
