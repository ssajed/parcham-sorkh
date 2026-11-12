<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Accompaniment\Models\AccompanimentOffer;
use App\Modules\Accompaniment\Models\AccompanimentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccompanimentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_companion_seeker_can_create_a_one_way_request(): void
    {
        $user = $this->makeUser(seeker: true);

        $response = $this->actingAs($user)->post(route('companion-seeker.requests.store'), [
            'origin_city' => 'تهران',
            'origin_location' => 'میدان آزادی',
            'destination_city' => 'کرج',
            'destination_location' => 'میدان شهدا',
            'type' => 'one_way',
            'departure_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
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
        $user = $this->makeUser(helper: true);

        $this->actingAs($user)
            ->get(route('companion-seeker.requests.create'))
            ->assertForbidden();
    }

    public function test_approved_helper_can_see_open_requests_that_fit_vehicle_capacity(): void
    {
        $seeker = $this->makeUser(seeker: true);
        $helper = $this->makeUser(helper: true, capacity: 3);

        $visible = $this->makeRequest($seeker, passengers: 2);
        $this->makeRequest($seeker, passengers: 4);

        $this->actingAs($helper)
            ->get(route('companion-seeker.helper.requests.index'))
            ->assertOk()
            ->assertSee('تهران')
            ->assertSee('کرج')
            ->assertSee((string) $visible->id);
    }

    public function test_helper_can_offer_and_seeker_can_accept_with_contact_details_released_only_after_agreement(): void
    {
        $seeker = $this->makeUser(seeker: true);
        $helper = $this->makeUser(helper: true, capacity: 3);
        $accompanimentRequest = $this->makeRequest($seeker, passengers: 2);

        $this->actingAs($helper)
            ->post(route('companion-seeker.helper.requests.offer', $accompanimentRequest))
            ->assertRedirect();

        $this->assertDatabaseHas('accompaniment_offers', [
            'accompaniment_request_id' => $accompanimentRequest->id,
            'helper_id' => $helper->id,
            'reserved_passenger_count' => 2,
            'status' => AccompanimentOffer::STATUS_PENDING,
        ]);

        $offer = AccompanimentOffer::query()->firstOrFail();

        $this->actingAs($seeker)
            ->get(route('companion-seeker.requests.index'))
            ->assertOk()
            ->assertSee('اعلام آمادگی همراه‌یاران')
            ->assertDontSee($helper->mobile);

        $this->actingAs($seeker)
            ->post(route('companion-seeker.requests.offers.accept', [$accompanimentRequest, $offer]))
            ->assertRedirect();

        $this->assertDatabaseHas('accompaniment_requests', [
            'id' => $accompanimentRequest->id,
            'status' => AccompanimentRequest::STATUS_AGREED,
        ]);
        $this->assertDatabaseHas('accompaniment_offers', [
            'id' => $offer->id,
            'status' => AccompanimentOffer::STATUS_ACCEPTED,
        ]);

        $this->actingAs($seeker)
            ->get(route('companion-seeker.requests.index'))
            ->assertOk()
            ->assertSee($helper->mobile);
    }

    public function test_helper_with_insufficient_vehicle_capacity_cannot_offer(): void
    {
        $seeker = $this->makeUser(seeker: true);
        $helper = $this->makeUser(helper: true, capacity: 1);
        $accompanimentRequest = $this->makeRequest($seeker, passengers: 2);

        $this->actingAs($helper)
            ->post(route('companion-seeker.helper.requests.offer', $accompanimentRequest))
            ->assertSessionHasErrors('request');

        $this->assertDatabaseCount('accompaniment_offers', 0);
    }

    public function test_user_cannot_accept_an_offer_for_another_seekers_request(): void
    {
        $owner = $this->makeUser(seeker: true);
        $otherSeeker = $this->makeUser(seeker: true);
        $helper = $this->makeUser(helper: true, capacity: 3);
        $accompanimentRequest = $this->makeRequest($owner, passengers: 2);
        $offer = AccompanimentOffer::query()->create([
            'accompaniment_request_id' => $accompanimentRequest->id,
            'helper_id' => $helper->id,
            'reserved_passenger_count' => 2,
            'status' => AccompanimentOffer::STATUS_PENDING,
        ]);

        $this->actingAs($otherSeeker)
            ->post(route('companion-seeker.requests.offers.accept', [$accompanimentRequest, $offer]))
            ->assertForbidden();

        $this->assertDatabaseHas('accompaniment_offers', [
            'id' => $offer->id,
            'status' => AccompanimentOffer::STATUS_PENDING,
        ]);
    }

    public function test_seeker_can_cancel_an_open_request_and_pending_capacity_reservations_are_released(): void
    {
        $seeker = $this->makeUser(seeker: true);
        $helper = $this->makeUser(helper: true, capacity: 3);
        $accompanimentRequest = $this->makeRequest($seeker, passengers: 2);
        $offer = AccompanimentOffer::query()->create([
            'accompaniment_request_id' => $accompanimentRequest->id,
            'helper_id' => $helper->id,
            'reserved_passenger_count' => 2,
            'status' => AccompanimentOffer::STATUS_PENDING,
        ]);

        $this->actingAs($seeker)
            ->post(route('companion-seeker.requests.cancel', $accompanimentRequest))
            ->assertRedirect();

        $this->assertDatabaseHas('accompaniment_requests', [
            'id' => $accompanimentRequest->id,
            'status' => AccompanimentRequest::STATUS_CANCELLED,
        ]);
        $this->assertDatabaseHas('accompaniment_offers', [
            'id' => $offer->id,
            'status' => AccompanimentOffer::STATUS_WITHDRAWN,
        ]);
    }

    private function makeUser(bool $seeker = false, bool $helper = false, ?int $capacity = null): User
    {
        $user = User::factory()->create();
        $user->role = $seeker ? User::ROLE_COMPANION_SEEKER : User::ROLE_COMPANION_HELPER;
        $user->account_status = User::ACCOUNT_STATUS_APPROVED;
        $user->can_be_companion_seeker = $seeker;
        $user->can_be_companion_helper = $helper;
        $user->vehicle_capacity = $capacity;
        $user->blocked_at = null;
        $user->save();

        return $user;
    }

    private function makeRequest(User $seeker, int $passengers): AccompanimentRequest
    {
        return $seeker->accompanimentRequests()->create([
            'origin_city' => 'تهران',
            'origin_location' => 'محدوده آزادی',
            'destination_city' => 'کرج',
            'destination_location' => 'محدوده آزادگان',
            'type' => AccompanimentRequest::TYPE_ONE_WAY,
            'departure_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'passenger_count' => $passengers,
            'status' => AccompanimentRequest::STATUS_OPEN,
        ]);
    }
}
