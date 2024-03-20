<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['id' => RoleEnum::ADMIN], ['name' => 'Admin']);
        Role::firstOrCreate(['id' => RoleEnum::MANAGER], ['name' => 'Manager']);
        Role::firstOrCreate(['id' => RoleEnum::SELLER], ['name' => 'Seller']);
        Role::firstOrCreate(['id' => RoleEnum::CLIENT], ['name' => 'Client']);

        DB::statement("SELECT setval('roles_id_seq', " .  (Role::max('id') ?? 1) . ");");
    }
}
