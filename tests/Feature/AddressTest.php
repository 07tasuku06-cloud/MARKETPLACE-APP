<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_change_page_can_be_displayed(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/purchase/address/' . $product->id);

        $response->assertStatus(200);
    }

    public function test_changed_address_is_displayed_on_purchase_page(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->post('/purchase/address/' . $product->id, [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building' => '変更ビル202',
            ]);

        $response = $this
            ->actingAs($user)
            ->get('/purchase/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市');
        $response->assertSee('変更ビル202');
    }

    public function test_changed_address_is_saved_in_order(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->post('/purchase/address/' . $product->id, [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building' => '変更ビル202',
            ]);

        $this
            ->actingAs($user)
            ->post('/purchase/' . $product->id, [
                'payment_method' => 'コンビニ払い',
                'postal_code' => session('purchase_postal_code'),
                'address' => session('purchase_address'),
                'building' => session('purchase_building'),
            ]);

        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '変更ビル202',
        ]);
    }
}
