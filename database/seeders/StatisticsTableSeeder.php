<?php

namespace Database\Seeders;

use App\Models\Statistic;
use Illuminate\Database\Seeder;

class StatisticsTableSeeder extends Seeder
{
    public function run()
    {
        $statistics = [
            [
                'name_uz' => 'Abonent soni',
                'name_ru' => 'Количество абонентов',
                'name_en' => 'Number of subscribers',
                'count'   => 360504,
            ],
            [
                'name_uz' => 'Qamrovga olingan mahallalar',
                'name_ru' => 'Охваченные махалли',
                'name_en' => 'Covered neighborhoods',
                'count'   => 534,
            ],
            [
                'name_uz' => 'Qamrovga olingan aholi soni',
                'name_ru' => 'Население под охватом',
                'name_en' => 'Covered population',
                'count'   => 1280941,
            ],
            [
                'name_uz' => 'Online kuzatuv uskunalari o‘rnatilgan (GPS)',
                'name_ru' => 'Установлено онлайн-оборудование (GPS)',
                'name_en' => 'Installed online monitoring devices (GPS)',
                'count'   => 191,
            ],
        ];

        Statistic::insert($statistics);
    }
}
