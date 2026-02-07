<?php

namespace Tests\Feature\Payment;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_process_payment_for_confirmed_order(): void
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $order = \App\Models\Order::factory()
            ->confirmed()
            ->create();

        $response = $this->withHeader(
            'Authorization',
            "Bearer {$token}"
        )->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'successful',
            ]);
    }
}
