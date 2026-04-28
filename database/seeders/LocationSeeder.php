<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CityGuideCategory;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $hotelsCategory = CityGuideCategory::where('name', 'فنادق')->first();
        if ($hotelsCategory) {
            Location::updateOrCreate(
                ['name' => 'فندق الجامعة'],
                ['city_guide_category_id' => $hotelsCategory->id, 'latitude' => 31.1143, 'longitude' => 30.9416]
            );
        }
        // Add more locations for other categories...
    }
}
