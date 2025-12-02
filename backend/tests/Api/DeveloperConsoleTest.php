<?php

namespace Tests\Api;

use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperConsoleTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsDeveloper(): User
    {
        $user = User::factory()
            ->for(RoleFactory::new()->state([
                'slug' => 'developer',
                'name' => 'Developer',
            ]), 'role')
            ->create();

        $this->actingAs($user, 'sanctum');

        return $user;
    }

    public function test_modules_endpoint_returns_versions_descending(): void
    {
        $this->actingAsDeveloper();
        $module = Module::factory()->create();
        ModuleVersion::factory()->create([
            'module_id' => $module->id,
            'version' => '1.0.0',
            'released_at' => now()->subDays(2),
        ]);
        ModuleVersion::factory()->create([
            'module_id' => $module->id,
            'version' => '1.1.0',
            'released_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/dev-console/modules');

        $response->assertOk()
            ->assertJsonFragment(['id' => $module->id])
            ->assertJsonPath('0.versions.0.version', '1.1.0');
    }

    public function test_publish_version_creates_release_and_log(): void
    {
        $this->actingAsDeveloper();
        $module = Module::factory()->create();

        $payload = [
            'version' => '2.0.0',
            'changelog' => 'Major release',
            'is_stable' => true,
        ];

        $response = $this->postJson("/api/dev-console/modules/{$module->id}/versions", $payload);

        $response->assertCreated()
            ->assertJsonFragment(['version' => '2.0.0'])
            ->assertJsonPath('module_id', $module->id);

        $this->assertDatabaseHas('module_versions', [
            'module_id' => $module->id,
            'version' => '2.0.0',
        ]);

        $this->assertDatabaseHas('update_logs', [
            'status' => 'released',
            'message' => 'Module version published',
        ]);
    }

    public function test_update_log_creates_status_entry(): void
    {
        $this->actingAsDeveloper();
        $version = ModuleVersion::factory()->create();

        $payload = [
            'status' => 'running',
            'message' => 'Deployment started',
            'context' => ['host' => 'node-1'],
        ];

        $response = $this->postJson("/api/dev-console/versions/{$version->id}/logs", $payload);

        $response->assertCreated()
            ->assertJsonFragment(['status' => 'running'])
            ->assertJsonPath('module_version_id', $version->id);

        $this->assertDatabaseHas('update_logs', [
            'module_version_id' => $version->id,
            'status' => 'running',
            'message' => 'Deployment started',
        ]);
    }
}
