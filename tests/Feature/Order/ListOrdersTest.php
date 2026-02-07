<?php

namespace Tests\Feature\Order;

use App\Models\User;
use Tests\TestCase;

final class ListOrdersTest extends TestCase
{

    public function test_orders_are_paginated(): void
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader(
            'Authorization',
            "Bearer {$token}"
        )->getJson('/api/orders?per_page=1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

}
