<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CityGuideCategory;

class CityGuideCategorySeeder extends Seeder
{
    public function run(): void
    {
        CityGuideCategory::updateOrCreate(['name' => 'فنادق'], ['icon_class' => 'fas fa-bed']);
        CityGuideCategory::updateOrCreate(['name' => 'مستشفيات حكومية'], ['icon_class' => 'far fa-hospital']);
        CityGuideCategory::updateOrCreate(['name' => 'مستشفيات خاصة'], ['icon_class' => 'fas fa-hospital-user']);
        CityGuideCategory::updateOrCreate(['name' => 'نوادي و متنزهات'], ['icon_class' => 'fas fa-tree']);
        CityGuideCategory::updateOrCreate(['name' => 'مواقف للمواصلات العامة'], ['icon_class' => 'fas fa-bus-alt']);
    }
}
