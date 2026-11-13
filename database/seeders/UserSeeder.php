<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // 'id' => 1,
            'role_id' => 1,
            'name' => 'Raymund C. Bagro',
            'username' => 'rcbagro',
            'emp_id' => '14116',
            'email' => 'rcbagro@ugecorp.com',
            'email_verified_at' => null,
            'password' => Hash::make('aljayugec'),
            // OR use Hash::make('your-password')
            'password_expire' => null,
            'password_reset' => 0,
            'active' => 1,
            'remember_token' => null,
            'guid' => 'fb978d6c-52e0-4bf3-b007-828b7da57a52',
            'domain' => 'default',
            'created_at' => '2026-01-15 11:27:58.997',
            'updated_at' => '2026-01-15 11:27:58.997',
        ]);

        
        DB::table('users')->insert([
            // 'id' => 1,
            'role_id' => 2,
            'name' => 'Sales User',
            'username' => 'sales1',
            'emp_id' => '99998',
            'email' => 'rcbagro@ugecorp.com',
            'email_verified_at' => null,
            'password' => bcrypt('1'),
            // OR use Hash::make('your-password')
            'password_expire' => null,
            'password_reset' => 0,
            'active' => 1,
            'remember_token' => null,
            'guid' => null,
            'domain' => null,
            'created_at' => '2026-01-15 11:27:58.997',
            'updated_at' => '2026-01-15 11:27:58.997',
        ]);

        DB::table('users')->insert([
            // 'id' => 1,
            'role_id' => 2,
            'name' => 'Costing User',
            'username' => 'costing1',
            'emp_id' => '99999',
            'email' => 'rcbagro@ugecorp.com',
            'email_verified_at' => null,
            'password' => bcrypt('1'),
            // OR use Hash::make('your-password')
            'password_expire' => null,
            'password_reset' => 0,
            'active' => 1,
            'remember_token' => null,
            'guid' => null,
            'domain' => null,
            'created_at' => '2026-01-15 11:27:58.997',
            'updated_at' => '2026-01-15 11:27:58.997',
        ]);
    }
}
