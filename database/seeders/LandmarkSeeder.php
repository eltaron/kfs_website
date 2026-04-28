<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Landmark;

class LandmarkSeeder extends Seeder
{
    public function run(): void
    {
        Landmark::updateOrCreate(['name' => 'متحف كفر الشيخ'], [
            'thumbnail' => '/placeholders/landmark1.jpg',
            'details' => '<h3>نبذة تاريخية</h3><p>تفاصيل غنية عن تاريخ المتحف...</p>',
            'order' => 1
        ]);
        Landmark::updateOrCreate(['name' => 'جزيرة الشخلوبة'], [
            'thumbnail' => '/placeholders/landmark2.jpg',
            'details' => '<h3>عن الجزيرة</h3><p>وصف طبيعة وجاذبية الجزيرة...</p>',
            'order' => 2
        ]);
        // Add more...
    }
}
