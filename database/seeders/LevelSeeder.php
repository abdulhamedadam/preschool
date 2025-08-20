<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ public function run(): void
    {
        DB::table('levels')->insert([
            [
                'name' => 'المستوى التمهيدي',
                'description' => 'تمهيدي قبل رياض الأطفال',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KG1',
                'description' => 'المرحلة الأولى من رياض الأطفال',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KG2',
                'description' => 'المرحلة الثانية من رياض الأطفال',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
