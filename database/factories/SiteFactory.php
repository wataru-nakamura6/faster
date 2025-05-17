<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SiteFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected $model = Site::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->realText(10),
            'status' => fake()->numberBetween(0, 1),
            'user_id' => User::factory(),
            'url' => fake()->url(),
            'uuid' => Str::random(10),
        ];
    }
}
