<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sell_products_are_displayed_on_mypage(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Product::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('出品商品');
    }

    public function test_bought_products_are_displayed_on_mypage(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create([
            'name' => '購入商品',
        ]);

        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'price' => $product->price,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入商品');
    }

    public function test_user_name_is_displayed_on_mypage(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => 'マイページユーザー',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('マイページユーザー');
    }
}
