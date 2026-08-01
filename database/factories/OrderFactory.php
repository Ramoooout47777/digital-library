<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $total = fake()->randomFloat(2, 5, 100);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.strtoupper(fake()->unique()->bothify('##########')),
            'subtotal' => $total,
            'discount_amount' => 0,
            'total' => $total,
            'payment_method' => 'card',
            'payment_status' => 'pending',
            'status' => Order::STATUS_PENDING,
            'order_status' => Order::STATUS_PENDING,
        ];
    }
}
