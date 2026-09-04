<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_requests_to_admin_routes_are_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/leads')->assertRedirect('/admin/login');
        $this->get('/admin/ai-agent')->assertRedirect('/admin/login');
    }

    public function test_guest_can_view_login_page_without_redirect_loop(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('LaraforgeX — Admin Login');
        $response->assertSee('laraforgex-http-progress', false);
        $response->assertSee('laraforgex-http-spinner', false);
    }

    public function test_authenticated_user_visiting_login_page_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'web')
            ->get('/admin/login')
            ->assertRedirect('/admin/dashboard');
    }

    public function test_user_with_token_cookie_is_auto_logged_in_and_redirected(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $token = $user->createToken('test_cookie_token')->plainTextToken;

        $response = $this->withUnencryptedCookie('laraforgex_auth_token', $token)
            ->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_unauthenticated_requests_to_sensitive_apis_return_401(): void
    {
        $this->getJson('/api/v1/database-manager')->assertStatus(401);
        $this->postJson('/api/v1/agent/chat', ['message' => 'hello'])->assertStatus(401);
        $this->getJson('/api/v1/agent/conversations')->assertStatus(401);
    }

    public function test_maintenance_routes_require_authentication(): void
    {
        $this->get('/clear-cache')->assertRedirect('/admin/login');
        $this->get('/migrate-db')->assertRedirect('/admin/login');
        $this->get('/seed-db')->assertRedirect('/admin/login');
        $this->get('/storage-link')->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_access_admin_dashboard_and_ai_agent(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($user, 'web')
            ->get('/admin/dashboard')
            ->assertStatus(200);

        $this->actingAs($user, 'web')
            ->get('/admin/ai-agent')
            ->assertStatus(200);
    }
}
