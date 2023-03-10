<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'name' => 'Ashanti'
        ]);

        Region::create([
            'name' => 'Volta'
        ]);

        Region::create([
            'name' => 'Northern'
        ]);

        Region::create([
            'name' => 'Eastern'
        ]);

        Region::create([
            'name' => 'Central'
        ]);

        Region::create([
            'name' => 'Upper West'
        ]);

        Region::create([
            'name' => 'Western'
        ]);

        Region::create([
            'name' => 'Greater Accra'
        ]);

        Region::create([
            'name' => 'Upper East'
        ]);

        Region::create([
            'name' => 'Bono East'
        ]);

        Region::create([
            'name' => 'Western North'
        ]);

        Region::create([
            'name' => 'Ahafo'
        ]);

        Region::create([
            'name' => 'North East'
        ]);

        Region::create([
            'name' => 'Oti'
        ]);

        Region::create([
            'name' => 'Bono'
        ]);

        Region::create([
            'name' => 'Brong Ahafo'
        ]);
    }
}
