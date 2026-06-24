<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_like_product(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/like/' . $product->id);

        $response->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_unlike_product(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post('/like/' . $product->id);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->post('/like/' . $product->id);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_like_count_is_displayed_on_product_detail(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post('/like/' . $product->id);

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_liked_product_is_displayed_on_mylist(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $likedProduct = Product::factory()->create([
            'name' => 'いいね済み商品',
        ]);

        $notLikedProduct = Product::factory()->create([
            'name' => '未いいね商品',
        ]);

        $this->actingAs($user)
            ->post('/like/' . $likedProduct->id);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね済み商品');
        $response->assertDontSee('未いいね商品');
    }
}
