<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
        'text' => $this->faker->sentence,
        'media' => 'default.png',
        'user_id' => \App\Models\User::factory(),
        'community_id' => 1,
        'created_at' => now(),
    ];
    }
}
