<?php

namespace Database\Seeders;

use App\Models\StudyMonthes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyMonthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ public function run(): void
    {
        $studyMonths = [1, 2, 3, 4, 8, 9, 10, 11];

        foreach ($studyMonths  as $month) {
            StudyMonthes::create([
                'name' => 'شهر ' . $month,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
