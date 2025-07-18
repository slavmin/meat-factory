<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected array $products;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->products = Product::factory()->count(3)->create([
            'stock' => 10,
        ])->toArray();

        $this->actingAs($this->user);
    }

    #[Test]
    public function authenticated_user_can_create_order()
    {
        $response = $this->postJson('/api/orders', [
            'user_id' => $this->user->id,
            'items' => [
                ['product_id' => $this->products[0]['id'], 'quantity' => 2],
                ['product_id' => $this->products[1]['id'], 'quantity' => 1],
            ],
            'comment' => 'Test order comment',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'total',
                'status',
                'comment',
            ]);
    }

    #[Test]
    public function order_creation_fails_with_insufficient_stock()
    {
        $response = $this->postJson('/api/orders', [
            'user_id' => $this->user->id,
            'items' => [
                ['product_id' => $this->products[0]['id'], 'quantity' => 15], // Больше чем есть
                ['product_id' => $this->products[1]['id'], 'quantity' => 1],
            ],
            'comment' => 'Test order with insufficient stock',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Order creation failed',
                'error' => "Not enough stock for product {$this->products[0]['id']}, only 10 available",
            ]);
    }

    #[Test]
    public function order_requires_valid_items()
    {
        $response = $this->postJson('/api/orders', [
            'user_id' => $this->user->id,
            'items' => [], // Пустой массив товаров
            'comment' => 'Test invalid order',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    #[Test]
    public function unauthenticated_user_cannot_create_order()
    {
        auth()->logout();

        $response = $this->postJson('/api/orders', [
            'user_id' => $this->user->id,
            'items' => [
                ['product_id' => $this->products[0]['id'], 'quantity' => 2],
            ],
            'comment' => 'Test unauthorized order',
        ]);

        $response->assertStatus(401);
    }
}
