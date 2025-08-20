<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
 $governoratesWithCities = [
    'السادات' => [

        'المنطقة 1',
        'المنطقة 2',
        'المنطقة 3',
        'المنطقة 4',
        'المنطقة 5',
        'المنطقة 6',
        'المنطقة 7',
        'المنطقة 8',
        'المنطقة 9',
        'المنطقة 10',
        'المنطقة 11',
        'المنطقة 12',
        'المنطقة13',
        'المنطقة14',
        'المنطقة15',
    ],
];

        foreach ($governoratesWithCities as $governorate => $cities) {
            $gov = Country::create([
              
                'name' => $governorate,
                'parent_id' => null
            ]);

            foreach ($cities as $city) {
               // dd($gov);
                City::create([
                
                    'name' => $city,
                    'parent_id' => $gov->id
                ]);
            }
        }
    }

}
