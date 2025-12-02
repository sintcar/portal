<?php

namespace Tests\Unit;

use App\Models\ModuleVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleVersionTest extends TestCase
{
    use RefreshDatabase;

    public function test_stable_scope_filters_unstable_versions(): void
    {
        $stable = ModuleVersion::factory()->create(['is_stable' => true]);
        ModuleVersion::factory()->unstable()->create();

        $results = ModuleVersion::stable()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($stable));
    }
}
