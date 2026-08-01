<?php
// tests/Feature/OrderTest.php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'price' => 25.00,
            'final_price' => 25.00,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)
            ->post('/api/v1/orders', [
                'book_id' => $book->id,
                'payment_method' => 'card',
                'quantity' => 1,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'order',
                'payment_url',
            ],
            'message',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 25.00,
        ]);
    }

    public function test_user_cannot_create_order_for_purchased_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'price' => 25.00,
            'final_price' => 25.00,
            'stock' => 10,
        ]);

        // Create purchase
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'order_id' => Order::factory()->create(['user_id' => $user->id])->id,
            'price_paid' => 25.00,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->post('/api/v1/orders', [
                'book_id' => $book->id,
                'payment_method' => 'card',
            ]);

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'message' => 'You already purchased this book',
        ]);
    }

    public function test_user_can_view_orders()
    {
        $user = User::factory()->create();
        $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/api/v1/orders');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'order_number',
                    'total',
                    'status',
                ]
            ],
            'meta',
        ]);
    }

    public function test_user_can_complete_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 0,
        ]);

        $response = $this->actingAs($user)
            ->post("/api/v1/orders/{$order->id}/complete");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Order completed successfully',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }
}