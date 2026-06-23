<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_can_be_displayed(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage/profile');

        $response->assertStatus(200);
    }

    public function test_profile_values_are_displayed(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/mypage/profile', [
                'name' => '更新ユーザー',
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building_name' => '更新ビル202',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新ユーザー',
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building_name' => '更新ビル202',
        ]);
    }
}
