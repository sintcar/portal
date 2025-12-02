<?php

namespace Database\Factories;

use App\Models\ModuleVersion;
use App\Models\UpdateLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpdateLogFactory extends Factory
{
    protected $model = UpdateLog::class;

    public function definition(): array
    {
        return [
            'module_version_id' => ModuleVersion::factory(),
            'status' => 'pending',
            'message' => $this->faker->sentence(),
            'context' => ['source' => 'factory'],
        ];
    }
}
