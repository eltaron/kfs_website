<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => Post::inRandomOrder()->first()->id,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'content' => $this->faker->paragraph,
            'is_approved' => $this->faker->boolean(80), // 80% approved
        ];
    }
}
