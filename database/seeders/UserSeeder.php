<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //system admin user
        $sauser = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'phoneno' => '233243000000',
            'email' => 'superadmin@gmail.com',
            'email_verified_at' => date('Y-m-d'),
            'password' => Hash::make('password'),
            'department_id' => 1
        ]);

        if($sauser){
            $sauser->assignRole(['system-admin', 'claim-entry', 'audit', 'accounting']);
        }


        //claim entry user
         $cluser = User::create([
            'firstname' => 'Claim Entry',
            'lastname' => 'User',
            'phoneno' => '233243000001',
            'email' => 'claim@gmail.com',
            'email_verified_at' =>  date('Y-m-d'),
            'password' => Hash::make('password'),
            'department_id' => 2
        ]);

        if($cluser){
             $cluser->assignRole(['claim-entry']);
        }

        //audit user
         $auuser = User::create([
            'firstname' => 'Audit',
            'lastname' => 'User',
            'phoneno' => '233243000001',
            'email' => 'audit@gmail.com',
            'email_verified_at' =>  date('Y-m-d'),
            'password' => Hash::make('password'),
            'department_id' => 3
        ]);

        if($auuser){
             $auuser->assignRole(['audit']);
        }

        //accounting user
         $accuser = User::create([
            'firstname' => 'Accounting',
            'lastname' => 'User',
            'phoneno' => '233243000001',
            'email' => 'accounting@gmail.com',
            'email_verified_at' =>  date('Y-m-d'),
            'password' => Hash::make('password'),
            'department_id' => 4
        ]);

        if($accuser){
             $accuser->assignRole(['accounting']);
        }
    }
}