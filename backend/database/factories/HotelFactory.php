<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company().' Hotel',
            'slug' => Str::slug($this->faker->unique()->company()),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'contact_email' => $this->faker->safeEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
            'check_in_time' => '15:00',
            'check_out_time' => '11:00',
            'star_rating' => 4,
            'amenities' => ['wifi', 'pool'],
            'is_active' => true,
        ];
    }
}
