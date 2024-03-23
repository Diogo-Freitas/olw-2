<?php

use App\Models\User;
use App\Models\Seller;
use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Client;
use App\Models\Company;
use function Pest\Laravel\seed;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CompanySeeder;
use App\Providers\RouteServiceProvider;
use Database\Seeders\AddressSeeder;

beforeEach(function () {
    seed(RoleSeeder::class);
    seed(CompanySeeder::class);
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('sellers users can authenticate using the login screen', function () {
    $user = User::factory()
        ->state(['role_id' => RoleEnum::SELLER])
        ->has(Seller::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))
        ->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('sellers users can not authenticate with invalid password', function () {
    $user = User::factory()
        ->state(['role_id' => RoleEnum::SELLER])
        ->has(Seller::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))
        ->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('clients users can authenticate using the login screen', function () {
    seed(AddressSeeder::class);

    $user = User::factory()
        ->state(['role_id' => RoleEnum::CLIENT])
        ->has(Client::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))
        ->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('clients users can not authenticate with invalid password', function () {
    seed(AddressSeeder::class);

    $user = User::factory()
        ->state(['role_id' => RoleEnum::CLIENT])
        ->has(Client::factory()
            ->state([
                'address_id' => Address::inRandomOrder()->value('id'),
                'company_id' => Company::inRandomOrder()->value('id'),
            ]))
        ->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('admins users can authenticate using the login screen', function () {
    seed(AddressSeeder::class);

    $user = User::factory()->state(['role_id' => RoleEnum::ADMIN])->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('admins users can not authenticate with invalid password', function () {
    seed(AddressSeeder::class);

    $user = User::factory()
        ->state(['role_id' => RoleEnum::ADMIN])->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});