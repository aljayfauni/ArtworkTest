<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN role
        DB::table('roles')->updateOrInsert(
            ['name' => 'ADMIN'], // use name as unique key
            [
                'access' => json_encode([
                    'MAINTENANCE',
                    'PRICE INDICATION',
                    'BOM CREATION',
                ]),
                'description' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // SALES role
        DB::table('roles')->updateOrInsert(
            ['name' => 'SALES'],
            [
                'access' => json_encode([
                    'PRICE INDICATION',
                    'BOM CREATION',
                ]),
                'description' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // COSTING role
        DB::table('roles')->updateOrInsert(
            ['name' => 'COSTING'],
            [
                'access' => json_encode([
                    'PRICE INDICATION',
                    'BOM CREATION',
                ]),
                'description' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
