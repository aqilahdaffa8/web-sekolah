<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies that RBAC middleware correctly:
 *  - Allows access to users with the correct role
 *  - Returns 403 to users with the wrong role
 *  - Returns 401 to unauthenticated requests
 */
class RbacMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $guruUser;

    private User $eskulUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);

        // Create users and assign roles
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->roles()->attach(
            Role::where('role_name', 'Super Admin')->first()
        );

        $this->guruUser = User::factory()->create();
        $this->guruUser->roles()->attach(
            Role::where('role_name', 'Guru')->first()
        );

        $this->eskulUser = User::factory()->create();
        $this->eskulUser->roles()->attach(
            Role::where('role_name', 'Eskul')->first()
        );
    }

    /** Super Admin can access /api/admin/users */
    public function test_super_admin_can_access_admin_users(): void
    {
        $response = $this->actingAs($this->superAdmin, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(200);
    }

    /** Guru role is blocked from /api/admin/users (403) */
    public function test_guru_cannot_access_admin_users(): void
    {
        $response = $this->actingAs($this->guruUser, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    /** Eskul role is blocked from /api/admin/users (403) */
    public function test_eskul_cannot_access_admin_users(): void
    {
        $response = $this->actingAs($this->eskulUser, 'sanctum')
            ->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    /** Eskul can access /api/eskul/achievements */
    public function test_eskul_can_access_achievements(): void
    {
        $response = $this->actingAs($this->eskulUser, 'sanctum')
            ->getJson('/api/eskul/achievements');

        $response->assertStatus(200);
    }

    /** Guru is blocked from /api/eskul/achievements (403) */
    public function test_guru_cannot_access_achievements(): void
    {
        $response = $this->actingAs($this->guruUser, 'sanctum')
            ->getJson('/api/eskul/achievements');

        $response->assertStatus(403);
    }

    /** Unauthenticated request returns 401 */
    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/admin/users')
            ->assertStatus(401);
    }

    /** Public endpoints are accessible without auth */
    public function test_public_home_is_accessible_without_auth(): void
    {
        $this->getJson('/api/public/home')
            ->assertStatus(200);
    }
}
