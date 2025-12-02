<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'is_default' => false,
        ];
    }

    public function default(): Factory
    {
        return $this->state(fn () => ['is_default' => true]);
    }
}
