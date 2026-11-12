<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_dashboard_displays_database_counts(): void
    {
        $administrator = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'account_approved_at' => now(),
        ]);

        User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'can_be_companion_seeker' => true,
        ]);

        User::factory()->create([
            'role' => User::ROLE_COMPANION_HELPER,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'can_be_companion_helper' => true,
        ]);

        User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_PENDING,
        ]);

        User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_REJECTED,
        ]);

        $this->actingAs($administrator)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats', function (array $stats): bool {
                return $stats['total_users'] === 5
                    && $stats['companion_seekers'] === 1
                    && $stats['companion_helpers'] === 1
                    && $stats['approved_accounts'] === 3
                    && $stats['pending_accounts'] === 1
                    && $stats['rejected_accounts'] === 1;
            });
    }

    public function test_non_administrator_cannot_open_the_management_panel(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
