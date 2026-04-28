<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(6),
            'slug' => $this->faker->unique()->slug,
            'thumbnail' => 'placeholders/post-placeholder.jpg', // صورة افتراضية
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(10)) . '</p>',
            'is_published' => true,
            'published_at' => now(),
            'allow_comments' => true,
        ];
    }
}
