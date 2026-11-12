<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class CompanionSeekerLoginPageTest extends TestCase
{
    public function test_authenticated_user_can_view_companion_login_page(): void
    {
        $this->actingAs(new User())
            ->get('/companion/login')
            ->assertOk()
            ->assertViewIs('auth.companion-login');
    }
}
