<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpensesItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            ['name' => 'إيجار',               'created_at' => $now, 'updated_at' => $now],
            ['name' => 'رواتب',               'created_at' => $now, 'updated_at' => $now],
            ['name' => 'مرافق (كهرباء/مياه)', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'إنترنت واتصالات',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'مستلزمات مكتبية',     'created_at' => $now, 'updated_at' => $now]


  
        ];

        DB::table('bnod_sarves')->insert($items);
    }
}
