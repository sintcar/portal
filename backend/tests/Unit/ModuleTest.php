<?php

namespace Tests\Unit;

use App\Models\License;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\UpdateLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_scope_returns_only_active_modules(): void
    {
        $active = Module::factory()->create();
        Module::factory()->inactive()->create();

        $results = Module::active()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($active));
    }

    public function test_relationships_eager_load_versions_and_licenses(): void
    {
        $module = Module::factory()->create();
        $versions = ModuleVersion::factory()->count(2)->for($module)->create();
        $licenses = License::factory()->count(2)->for($module)->create();
        UpdateLog::factory()->for($versions->first())->create();

        $loaded = Module::with(['versions', 'licenses', 'updateLogs'])->find($module->id);

        $this->assertCount(2, $loaded->versions);
        $this->assertCount(2, $loaded->licenses);
        $this->assertCount(1, $loaded->updateLogs);
    }
}
