<?php

namespace Tests\Integration;

use App\Models\License;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\UpdateLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_version_lifecycle_links_logs_and_licenses(): void
    {
        $module = Module::factory()->create();
        $version = ModuleVersion::factory()->for($module)->create([
            'version' => '3.0.0',
            'is_stable' => true,
        ]);
        $license = License::factory()->for($module)->create();

        UpdateLog::factory()->for($version)->create([
            'status' => 'successful',
            'message' => 'Deployment completed',
        ]);

        $freshModule = Module::with(['versions.updateLogs', 'licenses'])->find($module->id);

        $this->assertTrue($freshModule->licenses->first()->is($license));
        $this->assertTrue($freshModule->versions->first()->is($version));
        $this->assertSame('successful', $freshModule->versions->first()->updateLogs->first()->status);
    }
}
