<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_products_are_displayed(): void
    {
        Product::factory()->create([
            'name' => '商品A',
        ]);

        Product::factory()->create([
            'name' => '商品B',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    public function test_own_products_are_not_displayed(): void
    {
        $user = User::factory()->create();

        Product::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        Product::factory()->create([
            'name' => '他人の商品',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }

    public function test_sold_label_is_displayed_for_sold_products(): void
    {
        Product::factory()->create([
            'name' => '売却済み商品',
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    public function test_products_can_be_searched_by_name(): void
    {
        Product::factory()->create([
            'name' => '腕時計',
        ]);

        Product::factory()->create([
            'name' => 'ノートPC',
        ]);

        $response = $this->get('/?keyword=腕');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('ノートPC');
    }

    public function test_mylist_displays_only_liked_products(): void
    {
        $user = User::factory()->create();

        $likedProduct = Product::factory()->create([
            'name' => 'いいねした商品',
        ]);

        Product::factory()->create([
            'name' => 'いいねしていない商品',
        ]);

        $user->likedProducts()->attach($likedProduct->id);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_mylist_is_empty_for_guest_user(): void
    {
        Product::factory()->create([
            'name' => '未ログインでは見えない商品',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('未ログインでは見えない商品');
    }
}
