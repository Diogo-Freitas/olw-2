<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'address_id' => Address::inRandomOrder()->value('id'),
            'user_id' => User::factory()->create(['role_id' => RoleEnum::CLIENT]),
        ];
    }
}
