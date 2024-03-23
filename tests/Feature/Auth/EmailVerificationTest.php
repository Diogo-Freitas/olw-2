<?php

use App\Models\User;
use App\Models\Seller;
use App\Enums\RoleEnum;
use App\Models\Company;
use function Pest\Laravel\seed;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CompanySeeder;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use App\Providers\RouteServiceProvider;

beforeEach(function () {
    seed(RoleSeeder::class);
    seed(CompanySeeder::class);
});

test('email verification screen can be rendered', function () {
    $user = $user = User::factory()
        ->state(['role_id' => RoleEnum::SELLER])
        ->has(Seller::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))->create([
            'email_verified_at' => null,
        ]);

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $user = $user = User::factory()
        ->state(['role_id' => RoleEnum::SELLER])
        ->has(Seller::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))->create([
            'email_verified_at' => null,
        ]);

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(RouteServiceProvider::HOME . '?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = $user = User::factory()
        ->state(['role_id' => RoleEnum::SELLER])
        ->has(Seller::factory()
            ->state(['company_id' => Company::inRandomOrder()->value('id')]))->create([
            'email_verified_at' => null,
        ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
