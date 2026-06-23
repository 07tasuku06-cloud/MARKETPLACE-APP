<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_page_can_be_displayed(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/purchase/' . $product->id);

        $response->assertStatus(200);
    }

    public function test_user_can_purchase_product(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル101',
        ]);

        $product = Product::factory()->create([
            'price' => 5000,
            'is_sold' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/purchase/' . $product->id, [
                'payment_method' => 'コンビニ払い',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ]);

        $response->assertRedirect('/mypage?page=buy');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'コンビニ払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'price' => 5000,
        ]);
    }

    public function test_product_becomes_sold_after_purchase(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル101',
        ]);

        $product = Product::factory()->create([
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this
            ->actingAs($user)
            ->post('/purchase/' . $product->id, [
                'payment_method' => 'コンビニ払い',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_sold' => true,
        ]);
    }

    public function test_purchased_product_is_displayed_as_sold_on_product_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル101',
        ]);

        $product = Product::factory()->create([
            'name' => '購入済み商品',
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this
            ->actingAs($user)
            ->post('/purchase/' . $product->id, [
                'payment_method' => 'コンビニ払い',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('購入済み商品');
        $response->assertSee('SOLD');
    }

    public function test_purchased_product_is_displayed_on_mypage_buy_list(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル101',
        ]);

        $product = Product::factory()->create([
            'name' => 'マイページ購入商品',
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this
            ->actingAs($user)
            ->post('/purchase/' . $product->id, [
                'payment_method' => 'コンビニ払い',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('マイページ購入商品');
    }
}
