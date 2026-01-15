<?php

namespace Tests\Feature\Api;

use App\Models\IpAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IPAddressControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_list_ip_addresses()
    {
        Sanctum::actingAs(User::factory()->create());

        IpAddress::factory()->count(3)->create();

        $response = $this->getJson('/api/ip-address');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'address', 'label', 'comment'],
                ],
                'links',
                'meta',
            ]);
    }

    /** @test */
    public function authenticated_user_can_create_ip_address()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'address' => '192.168.1.1',
            'label' => 'Office Router',
            'comment' => 'Main office gateway',
        ];

        $response = $this->postJson('/api/ip-address/store', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'IP address created successfully.',
            ]);

        $this->assertDatabaseHas('ip_addresses', [
            'address' => '192.168.1.1',
            'label' => 'Office Router',
            'comment' => 'Main office gateway',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function ip_address_creation_requires_valid_data()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/ip-address/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['address']);
    }

    /** @test */
    public function authenticated_user_can_view_single_ip_address()
    {
        Sanctum::actingAs(User::factory()->create());

        $ipAddress = IpAddress::factory()->create();

        $response = $this->getJson("/api/ip-address/{$ipAddress->id}/show");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'address', 'label', 'comment'],
            ]);
    }

    /** @test */
    public function owner_can_update_ip_address()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $ipAddress = IpAddress::factory()->create([
            'user_id' => $user->id,
        ]);

        $payload = [
            'address' => '10.0.0.1',
            'label' => 'Updated Label',
            'comment' => 'Updated comment',
        ];

        $response = $this->postJson("/api/ip-address/{$ipAddress->id}/update", $payload);

        $response->assertOk()
            ->assertJson([
                'message' => 'IP address updated successfully.',
            ]);

        $this->assertDatabaseHas('ip_addresses', [
            'id' => $ipAddress->id,
            'address' => '10.0.0.1',
            'label' => 'Updated Label',
            'comment' => 'Updated comment',
        ]);
    }

    /** @test */
    public function non_owner_cannot_update_ip_address()
    {
        Event::fake();
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $ipAddress = IpAddress::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->postJson("/api/ip-address/{$ipAddress->id}/update", [
            'address' => '8.8.8.8',
            'label' => 'Updated Label',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function super_admin_can_delete_ip_address()
    {
        $user = User::factory()->create([
            'role' => 'super-admin',
        ]);
        Sanctum::actingAs($user);

        $ipAddress = IpAddress::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/ip-address/{$ipAddress->id}/delete");

        $response->assertOk()
            ->assertJson([
                'message' => 'IP address deleted successfully.',
            ]);

        $this->assertDatabaseMissing('ip_addresses', [
            'id' => $ipAddress->id,
        ]);
    }

    // /** @test */
    public function non_super_admin_cannot_delete_ip_address()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $ipAddress = IpAddress::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson("/api/ip-address/{$ipAddress->id}/delete");

        $response->assertForbidden();
    }
}
