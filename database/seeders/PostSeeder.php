<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 20 مقالًا عشوائيًا
        Post::factory(20)->create();

        // إنشاء 50 تعليقًا عشوائيًا على المقالات
        Comment::factory(50)->create();
    }
}
