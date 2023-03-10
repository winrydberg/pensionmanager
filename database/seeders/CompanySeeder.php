<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Ghana Rubber Estates Limited',
            'region_id' => 8
        ]);

        Company::create([
            'name' => 'Special Ice Company Ltd',
            'region_id' => 8
        ]);
    }
}
