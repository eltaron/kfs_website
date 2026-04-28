<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;

class StatisticSeeder extends Seeder
{
    public function run(): void
    {
        Statistic::updateOrCreate(['title' => 'التعداد السكاني للمحافظة'], ['number' => 3783559, 'icon_class' => 'fas fa-users', 'order' => 1]);
        Statistic::updateOrCreate(['title' => 'المساحة الكلية للمحافظة (كم²)'], ['number' => 3683, 'icon_class' => 'fas fa-map-marked-alt', 'order' => 2]);
        Statistic::updateOrCreate(['title' => 'عدد المراكز والمدن بالمحافظة'], ['number' => 14, 'icon_class' => 'fas fa-building', 'order' => 3]);
    }
}
