<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Enums\RoleEnum;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role_id' => RoleEnum::ADMIN,
        ]);
        
        User::factory()
            ->count(100)
            ->has(Seller::factory()
                ->hasSales(30))
            ->create();
    }
}
