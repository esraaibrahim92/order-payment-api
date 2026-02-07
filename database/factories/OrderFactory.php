<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_name'  => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'status'         => 'pending',
            'total'          => 0,
        ];
    }

    public function confirmed(): self
    {
        return $this->state(fn () => [
            'status' => 'confirmed',
        ]);
    }
}
