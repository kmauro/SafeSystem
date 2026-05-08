<?php

use App\Domains\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

test('login page can be rendered', function () {
    $this->get('/login')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('title', 'Log In'));
});

test('signup page can be rendered', function () {
    $this->get('/signup')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Signup')
            ->where('title', 'Sign Up'));
});

test('users can sign up', function () {
    $this->post('/signup', [
        'name' => 'Killian Mauro',
        'email' => 'killian@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'killian@example.com',
    ]);

    expect(Hash::check('password', User::firstOrFail()->password))->toBeTrue();
});

test('users can log in', function () {
    $user = User::factory()->create([
        'email' => 'killian@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->post('/login', [
        'email' => 'killian@example.com',
        'password' => 'password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('users cannot log in with invalid credentials', function () {
    User::factory()->create([
        'email' => 'killian@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->post('/login', [
        'email' => 'killian@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('users can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
