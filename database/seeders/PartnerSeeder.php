<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        Partner::updateOrCreate(
            ['name' => 'بوابة الأزهر الإلكترونية'],
            ['link' => '#', 'description' => '...وصف قصير...', 'image' => '/placeholders/azhar.png']
        );
        Partner::updateOrCreate(
            ['name' => 'دار الإفتاء المصرية'],
            ['link' => '#', 'description' => '...وصف قصير...', 'image' => '/placeholders/eftaa.png']
        );
        // Add more partners...
    }
}
