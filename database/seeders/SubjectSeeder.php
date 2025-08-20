<?php

namespace Database\Seeders;

use App\Models\Subjects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        Subjects::insert([
            [
                'name' => 'نور البيان',
                'description' => 'أساسيات تعلم القراءة والكتابة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'حساب',
                'description' => 'مادة الحساب الأساسية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Math',
                'description' => 'Mathematics in English',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'إنجليزي',
                'description' => 'اللغة الإنجليزية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'أنشطة',
                'description' => 'أنشطة فنية وحركية متنوعة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'محفوظات',
                'description' => 'أناشيد وأبيات للحفظ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'قيم',
                'description' => 'تعليم القيم والسلوكيات الإيجابية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'القرآن',
                'description' => 'تحفيظ وتلاوة القرآن الكريم',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
