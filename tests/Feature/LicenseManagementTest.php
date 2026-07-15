<?php

namespace Tests\Feature;

use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set test admin credentials in config
        config([
            'app.env' => 'testing',
            'app.admin_username' => 'testadmin',
            'app.admin_password' => 'testpass',
        ]);
    }

    /**
     * Helper to generate HTTP Basic Auth headers.
     */
    private function authHeaders(string $username = 'testadmin', string $password = 'testpass'): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode("{$username}:{$password}"),
        ];
    }

    public function test_admin_requires_authentication(): void
    {
        // Unauthenticated access
        $response = $this->get('/admin/licenses');
        $response->assertStatus(401);
        $response->assertHeader('WWW-Authenticate');

        // Invalid credentials
        $response = $this->get('/admin/licenses', $this->authHeaders('wrong', 'wrong'));
        $response->assertStatus(401);

        // Valid credentials
        $response = $this->get('/admin/licenses', $this->authHeaders());
        $response->assertStatus(200);
    }

    public function test_can_create_license(): void
    {
        $response = $this->post('/admin/licenses', [
            'customer_name' => 'ISP Merdeka',
            'target_domain' => 'merdeka.net',
            'target_ip' => '10.20.30.40',
            'max_routers' => 10,
            'expires_at' => now()->addDays(30)->toDateString(),
        ], $this->authHeaders());

        $response->assertRedirect('/admin/licenses');
        $this->assertDatabaseHas('licenses', [
            'customer_name' => 'ISP Merdeka',
            'target_domain' => 'merdeka.net',
            'target_ip' => '10.20.30.40',
            'max_routers' => 10,
        ]);

        $license = License::first();
        $this->assertNotNull($license->license_key);
        $this->assertStringStartsWith('NS-2026-', $license->license_key);
    }

    public function test_can_toggle_license_status(): void
    {
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-ABCD-1234',
            'target_domain' => 'merdeka.net',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->post("/admin/licenses/{$license->id}/toggle-status", [], $this->authHeaders());

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'suspended',
        ]);

        $this->assertEquals('suspended', $license->fresh()->status);
    }

    public function test_can_extend_validity(): void
    {
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-ABCD-1234',
            'target_domain' => 'merdeka.net',
            'status' => 'expired',
            'expires_at' => now()->subDays(5),
        ]);

        $response = $this->post("/admin/licenses/{$license->id}/extend-validity", [], $this->authHeaders());

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'active',
        ]);

        $this->assertTrue($license->fresh()->expires_at->isFuture());
    }

    public function test_api_license_validation_success(): void
    {
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-ABCD-1234',
            'target_domain' => 'merdeka.net',
            'status' => 'active',
            'max_routers' => 12,
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->postJson('/api/v1/validate-license', [
            'license_key' => 'NS-2026-ABCD-1234',
            'domain' => 'merdeka.net',
            'ip' => '10.20.30.40'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'valid',
            'max_routers' => 12,
        ]);

        $license = $license->fresh();
        $this->assertEquals('10.20.30.40', $license->last_ping_ip);
        $this->assertNotNull($license->last_ping_at);
    }

    public function test_api_license_validation_failures(): void
    {
        // 1. Non-existent key
        $response = $this->postJson('/api/v1/validate-license', [
            'license_key' => 'INVALID-KEY',
            'domain' => 'merdeka.net',
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'invalid',
            'message' => 'License expired or suspended'
        ]);

        // 2. Suspended key
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-SUSPENDED',
            'target_domain' => 'merdeka.net',
            'status' => 'suspended',
            'expires_at' => now()->addDays(30),
        ]);
        $response = $this->postJson('/api/v1/validate-license', [
            'license_key' => 'NS-2026-SUSPENDED',
            'domain' => 'merdeka.net',
        ]);
        $response->assertStatus(403);

        // 3. Expired key
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-EXPIRED',
            'target_domain' => 'merdeka.net',
            'status' => 'active',
            'expires_at' => now()->subDays(1),
        ]);
        $response = $this->postJson('/api/v1/validate-license', [
            'license_key' => 'NS-2026-EXPIRED',
            'domain' => 'merdeka.net',
        ]);
        $response->assertStatus(403);

        // 4. Domain mismatch
        $license = License::create([
            'customer_name' => 'ISP Merdeka',
            'license_key' => 'NS-2026-OK',
            'target_domain' => 'merdeka.net',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ]);
        $response = $this->postJson('/api/v1/validate-license', [
            'license_key' => 'NS-2026-OK',
            'domain' => 'other-domain.net',
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'invalid',
            'message' => 'License domain mismatch'
        ]);
    }
}
