<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

         Section::create([
            'title_uz' => 'Yangiliklar',
            'title_ru' => 'Новости',
            'title_en' => 'News',
            'slug_uz' => 'yangiliklar',
            'slug_ru' => '',
            'slug_en' => '',
            'parent_id' => null,
            'sort' => 1,
        ]);

        Section::create([
            'title_uz' => 'E\'lonlar',
            'title_ru' => 'Реклама',
            'title_en' => 'Advertisements',
            'slug_uz' => 'elonlar',
            'slug_ru' => '',
            'slug_en' => '',
            'parent_id' => null,
            'sort' => 2,
        ]);

        Section::create([
            'title_uz' => 'Kuzatuv kameralari',
            'title_ru' => 'Камеры наблюдения',
            'title_en' => 'Surveillance cameras',
            'slug_uz' => 'kuzatuv_kameralari',
            'slug_ru' => '',
            'slug_en' => '',
            'parent_id' => null,
            'sort' => 3,
        ]);

    }
}
