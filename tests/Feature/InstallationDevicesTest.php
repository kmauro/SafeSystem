<?php

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Models\Installation;
use App\Domains\Configs\Models\Category;
use App\Domains\Configs\Models\State;
use App\Domains\CRUD\Models\Device;
use App\Domains\CRUD\Models\DeviceType;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

function createInstallationDeviceUser(): User
{
    $roleId = DB::table('roles')->insertGetId([
        'role_name' => 'usuario',
    ]);

    return User::create([
        'name' => 'Killian',
        'surname' => 'Mauro',
        'username' => 'killian-installation',
        'email' => 'killian-installation@example.com',
        'password' => Hash::make('password'),
        'role_id' => $roleId,
    ]);
}

function createInstallationForDevices(User $user): Installation
{
    $client = Client::create([
        'name' => 'Cliente Demo',
        'phone' => '1111111111',
        'email' => 'cliente-installation@example.com',
    ]);

    $category = Category::create([
        'name' => 'Alarmas',
    ]);

    $state = State::create([
        'name' => 'Pendiente',
        'color' => 'yellow',
    ]);

    return Installation::create([
        'address' => 'Calle 123',
        'description' => 'Instalacion demo',
        'client_id' => $client->id,
        'category_id' => $category->id,
        'state_id' => $state->id,
        'assigned_user_id' => $user->id,
    ]);
}

test('installation view receives available and assigned devices', function () {
    $user = createInstallationDeviceUser();
    $installation = createInstallationForDevices($user);

    $deviceType = DeviceType::create([
        'type' => 'Camara',
    ]);

    $device = Device::create([
        'name' => 'Camara IP',
        'type_id' => $deviceType->id,
    ]);

    DB::table('installation_devices')->insert([
        'installation_id' => $installation->id,
        'device_id' => $device->id,
        'code' => 'CAM-001',
    ]);

    $this
        ->actingAs($user)
        ->get(route('installation.show', $installation))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('CRM/Installations/ViewInstallation')
            ->where('installation.devices.0.name', 'Camara IP')
            ->where('installation.devices.0.type.type', 'Camara')
            ->where('installation.devices.0.pivot.code', 'CAM-001')
            ->where('devices.0.name', 'Camara IP'));
});

test('device can be added to and removed from an installation', function () {
    $user = createInstallationDeviceUser();
    $installation = createInstallationForDevices($user);

    $deviceType = DeviceType::create([
        'type' => 'Sensor',
    ]);

    $device = Device::create([
        'name' => 'Sensor PIR',
        'type_id' => $deviceType->id,
    ]);

    $this
        ->actingAs($user)
        ->post(route('installation.devices.store', $installation), [
            'device_id' => $device->id,
            'code' => 'PIR-100',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('installation_devices', [
        'installation_id' => $installation->id,
        'device_id' => $device->id,
        'code' => 'PIR-100',
    ]);

    $installationDeviceId = DB::table('installation_devices')->value('id');

    $this
        ->actingAs($user)
        ->post(route('installation.devices.delete', [$installation->id, $installationDeviceId]))
        ->assertRedirect();

    $this->assertDatabaseMissing('installation_devices', [
        'id' => $installationDeviceId,
        'installation_id' => $installation->id,
    ]);
});
