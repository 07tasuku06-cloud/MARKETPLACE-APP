<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_can_be_displayed(): void
    {
        $product = Product::factory()->create([
            'name' => '詳細確認商品',
            'brand' => 'テストブランド',
            'description' => '商品の説明です',
            'price' => 12345,
            'condition' => '良好',
        ]);

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('詳細確認商品');
        $response->assertSee('テストブランド');
        $response->assertSee('商品の説明です');
        $response->assertSee('12,345');
        $response->assertSee('良好');
    }

    public function test_comments_are_displayed_on_product_detail(): void
    {
        $user = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $product = Product::factory()->create([
            'name' => 'コメント確認商品',
        ]);

        Comment::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'これはテストコメントです',
        ]);

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('コメントユーザー');
        $response->assertSee('これはテストコメントです');
        $response->assertSee('コメント(1)');
    }

    public function test_authenticated_user_can_post_comment(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/comment/' . $product->id, [
                'comment' => '投稿テストコメント',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => '投稿テストコメント',
        ]);
    }

    public function test_comment_is_required(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/item/' . $product->id)
            ->post('/comment/' . $product->id, [
                'comment' => '',
            ]);

        $response->assertRedirect('/item/' . $product->id);

        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);
    }

    public function test_comment_must_be_255_characters_or_less(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/item/' . $product->id)
            ->post('/comment/' . $product->id, [
                'comment' => str_repeat('あ', 256),
            ]);

        $response->assertRedirect('/item/' . $product->id);

        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以内で入力してください',
        ]);
    }
}
