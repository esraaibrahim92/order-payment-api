<?php

namespace App\Http\Controllers;

use App\Application\Payment\ProcessPaymentUseCase;
use Illuminate\Http\Request;

final class PaymentController extends Controller
{
    public function store(Request $request, ProcessPaymentUseCase $useCase)
    {
        $useCase->execute($request->order_id);
        return response()->json(['message' => 'Payment processed']);
    }
}
