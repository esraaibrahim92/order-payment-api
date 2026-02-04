<?php

namespace App\Http\Controllers;

use App\Application\Order\CreateOrderUseCase;
use Illuminate\Http\Request;

final class OrderController extends Controller
{
    public function store(Request $request, CreateOrderUseCase $useCase)
    {
        $order = $useCase->execute($request->items);
        return response()->json($order, 201);
    }
}
