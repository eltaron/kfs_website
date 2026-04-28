<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. الأساسيات والإعدادات
        // $this->call(SettingSeeder::class);
        // $this->call(RoleAndPermissionSeeder::class); // مهم: الأدوار أولاً
        // $this->call(UserSeeder::class);             // ثم المستخدمين لتعيين الأدوار

        // // 2. الفئات
        // $this->call(CategorySeeder::class);
        // $this->call(CityGuideCategorySeeder::class);

        // // 3. المحتوى الأساسي
        // $this->call(PostSeeder::class);
        // $this->call(ProjectSeeder::class);
        // $this->call(ServiceSeeder::class);
        // $this->call(InvestmentSeeder::class);
        // $this->call(LandmarkSeeder::class);
        // $this->call(PartnerSeeder::class);
        // $this->call(StatisticSeeder::class);
        // $this->call(LocationSeeder::class);
        // $this->call(HeroSliderSeeder::class);
        $this->call(InvestmentProjectSeeder::class);
    }
}
