<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ModuleVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleVersionFactory extends Factory
{
    protected $model = ModuleVersion::class;

    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'version' => $this->faker->unique()->numerify('1.#.#'),
            'changelog' => $this->faker->sentence(),
            'released_at' => now()->subDays($this->faker->numberBetween(0, 10)),
            'is_stable' => $this->faker->boolean(80),
        ];
    }

    public function unstable(): Factory
    {
        return $this->state(fn () => ['is_stable' => false]);
    }
}
