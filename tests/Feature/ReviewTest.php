<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function seedRoles(): void
    {
        UserRole::create(['name' => 'Admin', 'is_primary' => true, 'permissions' => []]);
        UserRole::create(['name' => 'Technician', 'is_primary' => false, 'permissions' => []]);
    }

    public function test_guest_cannot_create_review(): void
    {
        $this->seedRoles();
        $response = $this->postJson('/api/'.config('app.version','codehas').'/reviews', []);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_review(): void
    {
        $this->seedRoles();
        $user = User::factory()->create();
        $tech = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/'.config('app.version','codehas').'/reviews', [
            'technician_id' => $tech->id,
            'rating' => 5,
            'comment' => 'Great service',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('review.rating', 5);
    }
}
