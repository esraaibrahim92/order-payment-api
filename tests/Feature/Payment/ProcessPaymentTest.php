<?php

namespace Tests\Feature\Payment;

use App\Models\User;
use Tests\TestCase;

final class ProcessPaymentTest extends TestCase
{
    public function test_can_process_payment_for_confirmed_order(): void
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $orderId = 1; 

        $response = $this->withHeader(
            'Authorization',
            "Bearer {$token}"
        )->postJson('/api/payments', [
            'order_id' => $orderId,
            'payment_method' => 'credit_card',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'successful',
            ]);
    }
}
