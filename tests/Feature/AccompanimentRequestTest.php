<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccompanimentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_companion_seeker_can_create_a_one_way_request(): void
    {
        $user = User::factory()->create();
        $user->role = User::ROLE_COMPANION_SEEKER;
        $user->account_status = User::ACCOUNT_STATUS_APPROVED;
        $user->can_be_companion_seeker = true;
        $user->save();

        $response = $this->actingAs($user)->post(route('companion-seeker.requests.store'), [
            'origin_city' => 'تهران',
            'origin_location' => 'میدان آزادی',
            'destination_city' => 'کرج',
            'destination_location' => 'میدان شهدا',
            'type' => 'one_way',
            'departure_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'passenger_count' => '2',
            'description' => 'درخواست آزمایشی',
        ]);

        $response->assertRedirect(route('companion-seeker.requests.index'));

        $this->assertDatabaseHas('accompaniment_requests', [
            'user_id' => $user->id,
            'origin_city' => 'تهران',
            'destination_city' => 'کرج',
            'type' => 'one_way',
            'passenger_count' => 2,
            'status' => 'open',
        ]);
    }

    public function test_helper_only_account_cannot_create_a_seeker_request(): void
    {
        $user = User::factory()->create();
        $user->role = User::ROLE_COMPANION_HELPER;
        $user->account_status = User::ACCOUNT_STATUS_APPROVED;
        $user->can_be_companion_helper = true;
        $user->can_be_companion_seeker = false;
        $user->save();

        $this->actingAs($user)
            ->get(route('companion-seeker.requests.create'))
            ->assertForbidden();
    }
}
