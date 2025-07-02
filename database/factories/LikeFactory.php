<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
        /*
        'user_id' => \App\Models\User::factory(),
        'post_id' => \App\Models\Post::factory(),
        'created_at' => now(),
        */
        'user_id' => 24,
        'post_id' => \App\Models\Post::inRandomOrder()->value('id'),
        'created_at' => now(),
    ];
    }
}
