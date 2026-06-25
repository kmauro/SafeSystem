<?php

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Models\Installation;
use App\Domains\Configs\Models\Category;
use App\Domains\Configs\Models\State;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('installation dashboard shows state statistics with percentages', function () {
    $roleId = DB::table('roles')->insertGetId([
        'role_name' => 'usuario',
    ]);

    $user = User::create([
        'name' => 'Killian',
        'surname' => 'Mauro',
        'username' => 'killian',
        'email' => 'killian@example.com',
        'password' => Hash::make('password'),
        'role_id' => $roleId,
    ]);

    $client = Client::create([
        'name' => 'Cliente Demo',
        'phone' => '1111111111',
        'email' => 'cliente@example.com',
    ]);

    $category = Category::create([
        'name' => 'Alarmas',
    ]);

    $completed = State::create([
        'name' => 'Completada',
        'color' => 'green',
    ]);

    $paused = State::create([
        'name' => 'Pausada',
        'color' => 'stone',
    ]);

    $pending = State::create([
        'name' => 'Pendiente',
        'color' => 'yellow',
    ]);

    Installation::create([
        'address' => 'Calle 1',
        'description' => 'Instalacion uno',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $pending->id,
        'assigned_user_id' => $user->id,
    ]);

    Installation::create([
        'address' => 'Calle 2',
        'description' => 'Instalacion dos',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $pending->id,
        'assigned_user_id' => $user->id,
    ]);

    Installation::create([
        'address' => 'Calle 3',
        'description' => 'Instalacion tres',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $completed->id,
        'assigned_user_id' => $user->id,
    ]);

    $this
        ->actingAs($user)
        ->get(route('installation.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('CRM/Installations/Dashboard')
            ->where('title', 'Dashboard de instalaciones')
            ->where('installationStatistics.total', 3)
            ->has('installationStatistics.states', 3)
            ->where('installationStatistics.states.0.name', 'Completada')
            ->where('installationStatistics.states.0.count', 1)
            ->where('installationStatistics.states.0.percentage', 33.3)
            ->where('installationStatistics.states.1.name', 'Pausada')
            ->where('installationStatistics.states.1.count', 0)
            ->where('installationStatistics.states.1.percentage', 0)
            ->where('installationStatistics.states.2.name', 'Pendiente')
            ->where('installationStatistics.states.2.count', 2)
            ->where('installationStatistics.states.2.percentage', 66.7));
});

test('installations index can be opened with a state filter from the query string', function () {
    $roleId = DB::table('roles')->insertGetId([
        'role_name' => 'usuario',
    ]);

    $user = User::create([
        'name' => 'Killian',
        'surname' => 'Mauro',
        'username' => 'killian',
        'email' => 'killian@example.com',
        'password' => Hash::make('password'),
        'role_id' => $roleId,
    ]);

    $client = Client::create([
        'name' => 'Cliente Demo',
        'phone' => '1111111111',
        'email' => 'cliente@example.com',
    ]);

    $category = Category::create([
        'name' => 'Alarmas',
    ]);

    $completed = State::create([
        'name' => 'Completada',
        'color' => 'green',
    ]);

    $pending = State::create([
        'name' => 'Pendiente',
        'color' => 'yellow',
    ]);

    Installation::create([
        'address' => 'Calle filtrada',
        'description' => 'Instalacion filtrada',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $pending->id,
        'assigned_user_id' => $user->id,
    ]);

    Installation::create([
        'address' => 'Calle excluida',
        'description' => 'Instalacion excluida',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $completed->id,
        'assigned_user_id' => $user->id,
    ]);

    $this
        ->actingAs($user)
        ->get(route('installation.index', ['state' => $pending->id]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('CRM/Installations/index')
            ->where('filters.state', (string) $pending->id)
            ->has('installations', 1)
            ->where('installations.0.address', 'Calle filtrada'));
});
