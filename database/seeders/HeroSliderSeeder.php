<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlider;

class HeroSliderSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlider::updateOrCreate(
            ['title' => 'المحافظ يفتتح مشروع تطوير مدخل المدينة'],
            [
                'description' => 'خطوة جديدة نحو تطوير البنية التحتية وتحسين المظهر الجمالي.',
                'media_path' => '/placeholders/slider1.jpg',
                'media_type' => 'image',
                'link_url' => '/news/post-slug-1',
                'link_text' => 'قراءة المزيد',
                'order' => 1,
            ]
        );
    }
}
