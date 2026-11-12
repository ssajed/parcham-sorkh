<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAdministratorManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdministrator(): User
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'account_approved_at' => now(),
        ]);
    }

    public function test_administrator_can_grant_admin_access_to_an_approved_account(): void
    {
        $administrator = $this->makeAdministrator();
        $candidate = User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'can_be_companion_seeker' => true,
        ]);

        $this->actingAs($administrator)
            ->post(route('admin.administrators.store'), ['user_id' => $candidate->id])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $candidate->id,
            'role' => User::ROLE_ADMIN,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
        ]);
    }

    public function test_pending_account_cannot_be_promoted_to_administrator(): void
    {
        $administrator = $this->makeAdministrator();
        $candidate = User::factory()->create([
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_PENDING,
        ]);

        $this->actingAs($administrator)
            ->from(route('admin.administrators.index'))
            ->post(route('admin.administrators.store'), ['user_id' => $candidate->id])
            ->assertRedirect(route('admin.administrators.index'))
            ->assertSessionHasErrors('user_id');

        $this->assertDatabaseHas('users', [
            'id' => $candidate->id,
            'role' => User::ROLE_COMPANION_SEEKER,
        ]);
    }

    public function test_last_active_administrator_cannot_be_demoted(): void
    {
        $administrator = $this->makeAdministrator();

        $this->actingAs($administrator)
            ->from(route('admin.administrators.index'))
            ->post(route('admin.administrators.revoke', $administrator->id))
            ->assertRedirect(route('admin.administrators.index'))
            ->assertSessionHasErrors('administrator');

        $this->assertDatabaseHas('users', [
            'id' => $administrator->id,
            'role' => User::ROLE_ADMIN,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
        ]);
    }

    public function test_administrator_cannot_revoke_own_access_even_when_another_admin_exists(): void
    {
        $administrator = $this->makeAdministrator();
        $this->makeAdministrator();

        $this->actingAs($administrator)
            ->from(route('admin.administrators.index'))
            ->post(route('admin.administrators.revoke', $administrator->id))
            ->assertRedirect(route('admin.administrators.index'))
            ->assertSessionHasErrors('administrator');

        $this->assertDatabaseHas('users', [
            'id' => $administrator->id,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_administrator_can_revoke_another_admin_without_losing_companion_access(): void
    {
        $administrator = $this->makeAdministrator();
        $secondAdministrator = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'account_approved_at' => now(),
            'can_be_companion_seeker' => true,
            'can_be_companion_helper' => true,
        ]);

        $this->actingAs($administrator)
            ->post(route('admin.administrators.revoke', $secondAdministrator->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $secondAdministrator->id,
            'role' => User::ROLE_COMPANION_SEEKER,
            'can_be_companion_seeker' => true,
            'can_be_companion_helper' => true,
        ]);
    }
}
