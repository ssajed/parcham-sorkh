<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSuspensionTest extends TestCase
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

    private function makeCompanion(): User
    {
        return User::factory()->create([
            'name' => 'کاربر آزمایشی',
            'mobile' => '09123456789',
            'password' => Hash::make('SecurePass123'),
            'role' => User::ROLE_COMPANION_SEEKER,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'account_approved_at' => now(),
            'can_be_companion_seeker' => true,
        ]);
    }

    public function test_administrator_can_suspend_a_companion_account_with_a_reason(): void
    {
        $administrator = $this->makeAdministrator();
        $companion = $this->makeCompanion();

        $this->actingAs($administrator)
            ->post(route('admin.users.block', $companion->id), [
                'blocked_reason' => 'نقض قوانین سامانه',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $companion->id,
            'account_status' => User::ACCOUNT_STATUS_APPROVED,
            'blocked_reason' => 'نقض قوانین سامانه',
            'blocked_by' => $administrator->id,
        ]);

        $this->assertNotNull($companion->fresh()->blocked_at);
    }

    public function test_blocked_user_can_sign_in_but_only_sees_the_suspension_notice(): void
    {
        $administrator = $this->makeAdministrator();
        $companion = $this->makeCompanion();

        $this->actingAs($administrator)
            ->post(route('admin.users.block', $companion->id), [
                'blocked_reason' => 'نقض قوانین سامانه',
            ])
            ->assertRedirect();

        $this->post(route('companion-seeker.login.store'), [
            'mobile' => '09123456789',
            'password' => 'SecurePass123',
        ])->assertRedirect(route('companion-seeker.account-blocked'));

        $this->assertAuthenticatedAs($companion);

        $this->get(route('companion-seeker.account-blocked'))
            ->assertOk()
            ->assertSee('حساب کاربری شما مسدود شده است')
            ->assertSee('نقض قوانین سامانه');

        $this->get(route('companion-seeker.dashboard'))
            ->assertRedirect(route('companion-seeker.account-blocked'));

        $this->get(route('companion-seeker.requests.index'))
            ->assertRedirect(route('companion-seeker.account-blocked'));
    }

    public function test_administrator_can_unblock_a_user_and_restore_previous_access(): void
    {
        $administrator = $this->makeAdministrator();
        $companion = $this->makeCompanion();

        $this->actingAs($administrator)
            ->post(route('admin.users.block', $companion->id), [
                'blocked_reason' => 'نقض قوانین سامانه',
            ])
            ->assertRedirect();

        $this->post(route('admin.users.unblock', $companion->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $companion->id,
            'blocked_at' => null,
            'blocked_reason' => null,
            'blocked_by' => null,
            'can_be_companion_seeker' => true,
        ]);
    }

    public function test_administrator_cannot_suspend_another_administrator(): void
    {
        $administrator = $this->makeAdministrator();
        $anotherAdministrator = $this->makeAdministrator();

        $this->actingAs($administrator)
            ->from(route('admin.users.index'))
            ->post(route('admin.users.block', $anotherAdministrator->id), [
                'blocked_reason' => 'دلیل آزمایشی',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', [
            'id' => $anotherAdministrator->id,
            'role' => User::ROLE_ADMIN,
            'blocked_at' => null,
        ]);
    }

    public function test_suspension_requires_a_reason(): void
    {
        $administrator = $this->makeAdministrator();
        $companion = $this->makeCompanion();

        $this->actingAs($administrator)
            ->from(route('admin.users.index'))
            ->post(route('admin.users.block', $companion->id), [
                'blocked_reason' => '',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHasErrors('blocked_reason');

        $this->assertDatabaseHas('users', [
            'id' => $companion->id,
            'blocked_at' => null,
        ]);
    }
}
