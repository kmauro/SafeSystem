<?php

use App\Domains\CRUD\Models\Device;
use App\Domains\CRUD\Models\DeviceType;
use App\Domains\CRUD\Models\Feature;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

function createCrudUser(): User
{
    $roleId = DB::table('roles')->insertGetId([
        'role_name' => 'usuario',
    ]);

    return User::create([
        'name' => 'Killian',
        'surname' => 'Mauro',
        'username' => 'killian',
        'email' => 'killian@example.com',
        'password' => Hash::make('password'),
        'role_id' => $roleId,
    ]);
}

test('devices index can be filtered by search text and product type', function () {
    $user = createCrudUser();

    $cameraType = DeviceType::create([
        'type' => 'Camara',
    ]);

    $alarmType = DeviceType::create([
        'type' => 'Alarma',
    ]);

    $nightVision = Feature::create([
        'feature' => 'Vision nocturna',
    ]);

    $camera = Device::create([
        'name' => 'Camara IP',
        'type_id' => $cameraType->id,
    ]);
    $camera->features()->sync([$nightVision->id]);

    Device::create([
        'name' => 'Panel central',
        'type_id' => $alarmType->id,
    ]);

    $this
        ->actingAs($user)
        ->get(route('devices.index', ['search' => 'Camara', 'type' => $cameraType->id]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('CRUD/index')
            ->where('title', 'Dispositivos')
            ->where('filters.search', 'Camara')
            ->where('filters.type', (string) $cameraType->id)
            ->has('devices', 1)
            ->where('devices.0.name', 'Camara IP')
            ->where('devices.0.type.type', 'Camara')
            ->where('devices.0.features.0.feature', 'Vision nocturna'));
});

test('device can be created updated and deleted with features', function () {
    $user = createCrudUser();

    $cameraType = DeviceType::create([
        'type' => 'Camara',
    ]);

    $alarmType = DeviceType::create([
        'type' => 'Alarma',
    ]);

    $nightVision = Feature::create([
        'feature' => 'Vision nocturna',
    ]);

    $wifi = Feature::create([
        'feature' => 'WiFi',
    ]);

    $this
        ->actingAs($user)
        ->post(route('devices.store'), [
            'name' => 'Camara IP',
            'type_id' => $cameraType->id,
            'features' => [$nightVision->id],
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $device = Device::query()->firstOrFail();

    $this->assertDatabaseHas('devices', [
        'id' => $device->id,
        'name' => 'Camara IP',
        'type_id' => $cameraType->id,
    ]);

    $this->assertDatabaseHas('device_feature', [
        'device_id' => $device->id,
        'feature_id' => $nightVision->id,
    ]);

    $this
        ->actingAs($user)
        ->post(route('devices.update'), [
            'id' => $device->id,
            'name' => 'Panel central',
            'type_id' => $alarmType->id,
            'features' => [$wifi->id],
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('devices', [
        'id' => $device->id,
        'name' => 'Panel central',
        'type_id' => $alarmType->id,
    ]);

    $this->assertDatabaseHas('device_feature', [
        'device_id' => $device->id,
        'feature_id' => $wifi->id,
    ]);

    $this->assertDatabaseMissing('device_feature', [
        'device_id' => $device->id,
        'feature_id' => $nightVision->id,
    ]);

    $this
        ->actingAs($user)
        ->post(route('devices.delete', $device))
        ->assertRedirect(route('devices.index'));

    $this->assertDatabaseMissing('devices', [
        'id' => $device->id,
    ]);
});
