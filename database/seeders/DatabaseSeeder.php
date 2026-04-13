<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('designations')->insert([

            ['name' => 'HQ Admin', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
        // User::create([
        //     'name' => 'EMP',
        //     'email' => 'emp@isro.gov.in', 
        //     'username' => 'emp',
        //     'password' => Hash::make('password'),
        //     'role' => 'employee',
        //     'employee_code' => 'EMP001',
        //     'designation' => 'Scientist-SC',
        //     'centre' => 'VSSC',
        //     'is_active' => 1, 
        // ]);

     
        // User::create([
        //     'name' => 'HRD COORD',
        //     'email' => 'coord@isro.gov.in',
        //     'username' => 'hrd_coord_002',
        //     'password' => Hash::make('password'),
        //     'role' => 'coordinator',
        //     'designation' => 'HR Manager',
        //     'centre' => 'VSSC',
        //     'is_active' => 1,
        // ]);

        
        // User::create([
        //     'name' => 'ISRO HQ ADMIN',
        //     'email' => 'admin@isro.gov.in',
        //     'username' => 'hq_admin_01',
        //     'password' => Hash::make('password'),
        //     'role' => 'admin',
        //     'designation' => 'Director',
        //     'centre' => 'HQ',
        //     'is_active' => 1,
        // ]);

         User::create([
            'id' => 1,
            'prefix' => 'Mr.',
            'name' => 'ISRO HQ ADMIN',
            'email' => 'admin@isro.gov.in',
            'username' => 'hq_admin_01',
            'password' => Hash::make('password@123'),
            'role' => 'admin',
            'designation' => '1',
            'centre' => 'HQ',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
