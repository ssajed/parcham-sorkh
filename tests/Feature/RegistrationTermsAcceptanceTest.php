<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTermsAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_is_rejected_when_terms_are_not_accepted(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'کاربر آزمایشی',
            'national_code' => '0012345679',
            'mobile' => '09123456789',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
            'role' => 'companion_seeker',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('accepted_terms');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_records_the_accepted_terms_version_and_time(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'کاربر آزمایشی',
            'national_code' => '0012345679',
            'mobile' => '09123456789',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
            'role' => 'companion_seeker',
            'accepted_terms' => '1',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'national_code' => '0012345679',
            'mobile' => '09123456789',
            'terms_version' => '2026-11-12-v1',
        ]);

        $this->assertNotNull(
            \App\Models\User::query()
                ->where('mobile', '09123456789')
                ->value('terms_accepted_at')
        );
    }
}
