<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::updateOrCreate(
            ['title_line_1' => 'الحماية', 'title_line_2' => 'الاجتماعية'],
            ['description' => 'خدمات متعلقة بالدعم الاجتماعي كالعلاج على نفقة الدولة والتمويل العقاري وغيرها.', 'is_highlighted' => false]
        );
        Service::updateOrCreate(
            ['title_line_1' => 'الخدمات', 'title_line_2' => 'المدنية'],
            ['description' => 'خدمات متعلقة بالأحوال الشخصية والأحوال المدنية والتوثيق والمرور.', 'is_highlighted' => true]
        );
        Service::updateOrCreate(
            ['title_line_1' => 'خدمات', 'title_line_2' => 'الملكيات'],
            ['description' => 'خدمات متعلقة بالشهر العقاري والسجل العيني الزراعي والحضري.', 'is_highlighted' => false]
        );
    }
}
