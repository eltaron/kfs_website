<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(['slug' => 'news'], ['name' => 'أخبار المحافظة', 'description' => '...']);
        Category::updateOrCreate(['slug' => 'events'], ['name' => 'فعاليات ومناسبات', 'description' => '...']);
        Category::updateOrCreate(['slug' => 'achievements'], ['name' => 'إنجازات الدولة', 'description' => '...']);
    }
}
