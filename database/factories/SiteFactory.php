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
     * Site
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => fake()->numberBetween(0, 1),
            'user_id' => 1,
            'url' => fake()->url(),
            'uuid' => Str::random(10),
        ];
    }
}
