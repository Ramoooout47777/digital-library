<?php
// database/factories/OrderItemFactory.php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = \App\Models\OrderItem::class;

    public function definition()
    {
        $price = $this->faker->randomFloat(2, 5, 50);
        $quantity = $this->faker->numberBetween(1, 5);
        
        return [
            'order_id' => Order::factory(),
            'book_id' => Book::factory(),
            'book_title' => $this->faker->sentence(3),
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $this->faker->randomFloat(2, 0, 5),
            'total' => $price * $quantity,
        ];
    }
}