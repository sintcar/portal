<?php

namespace Database\Factories;

use App\Models\License;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'license_key' => Str::uuid()->toString(),
            'issued_to' => $this->faker->company(),
            'issued_at' => now(),
            'expires_at' => now()->addMonths(6),
            'status' => 'active',
            'metadata' => ['source' => 'factory'],
        ];
    }

    public function expired(): Factory
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'expires_at' => now()->subDay(),
        ]);
    }
}
