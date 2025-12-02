<?php

namespace Tests\Api;

use App\Models\License;
use App\Models\Module;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NetworkAdminTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsNetworkAdmin(): void
    {
        $user = User::factory()
            ->for(RoleFactory::new()->state([
                'slug' => 'network-admin',
                'name' => 'Network Admin',
            ]), 'role')
            ->create();

        $this->actingAs($user, 'sanctum');
    }

    public function test_modules_endpoint_includes_versions_and_licenses(): void
    {
        $this->actingAsNetworkAdmin();
        $module = Module::factory()->create();
        $module->versions()->createMany([
            ['version' => '1.0.0', 'released_at' => now()->subDays(3)],
            ['version' => '1.1.0', 'released_at' => now()->subDay()],
        ]);
        License::factory()->for($module)->count(2)->create();

        $response = $this->getJson('/api/network/modules');

        $response->assertOk()
            ->assertJsonFragment(['id' => $module->id])
            ->assertJsonCount(2, '0.licenses');
    }

    public function test_toggle_module_flips_activation_flag(): void
    {
        $this->actingAsNetworkAdmin();
        $module = Module::factory()->inactive()->create();

        $this->patchJson("/api/network/modules/{$module->id}/toggle")
            ->assertOk()
            ->assertJsonPath('is_active', true);

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'is_active' => true,
        ]);
    }

    public function test_issue_license_records_new_license(): void
    {
        $this->actingAsNetworkAdmin();
        $module = Module::factory()->create();

        $payload = [
            'issued_to' => 'QA Department',
            'metadata' => ['region' => 'EU'],
        ];

        $response = $this->postJson("/api/network/modules/{$module->id}/licenses", $payload);

        $response->assertCreated()
            ->assertJsonPath('module_id', $module->id)
            ->assertJsonPath('issued_to', 'QA Department');

        $this->assertDatabaseHas('licenses', [
            'module_id' => $module->id,
            'issued_to' => 'QA Department',
        ]);
    }
}
