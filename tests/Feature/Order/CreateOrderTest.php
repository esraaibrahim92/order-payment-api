<?php

namespace Tests\Feature\Order;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order(): void
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader(
            'Authorization',
            "Bearer {$token}"
        )->postJson('/api/orders', [
            'customer' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'items' => [
                [
                    'product_name' => 'Laptop',
                    'quantity' => 1,
                    'price' => 1000,
                ],
                [
                    'product_name' => 'Mouse',
                    'quantity' => 1,
                    'price' => 60,
                ],
            ],
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'total',
                'status',
            ])
            ->assertJson([
                'status' => 'pending',
            ]);
    }
}
