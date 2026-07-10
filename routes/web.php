<?php

use App\Domains\API\Controllers\APIController;
use App\Domains\Clients\Controllers\ClientController;
use App\Domains\Clients\Controllers\InstallationController;
use App\Domains\Configs\Controllers\Configcontroller;
use App\Domains\CRUD\Controllers\DeviceController;
use App\Domains\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->controller(UserController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::get('/signup', 'showSignup')->name('signup');

    Route::post('/login', 'login')->name('login.store');
    Route::post('/signup', 'signup')->name('signup.store');
});

Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/profile', 'showProfile')->name('profile');
    Route::get('/users', 'showUsers')->name('users.index');
    Route::get('/users/{id}', 'showUser')->name('users.show');
    Route::get('/user/forgot', 'showForgotPassword')->name('user.forgot');
    Route::post('/profile', 'updateProfile')->name('profile.update');
    Route::post('/profile/updatePassword', 'updatePassword')->name('profile.updatePassword');
});

Route::middleware('auth')->controller(ClientController::class)->group(function () {
    Route::get('/client/dashboard', 'showDashboard')->name('client.dashboard');
    Route::get('/client/add', 'showAddClient')->name('client.add');
    Route::get('/clients', 'showIndex')->name('clients.index');
    Route::get('/clients/{id}', 'showClient')->name('clients.show');

    Route::post('/client/add', 'addClient')->name('client.store');
    Route::post('/client/update', 'updateClient')->name('client.update');
    Route::post('/client/{id}/delete', 'deleteClient')->name('client.delete');
});

Route::middleware('auth')->controller(InstallationController::class)->group(function () {
    Route::get('/installation/dashboard', 'showDashboard')->name('installation.dashboard');
    Route::get('/installation/add', 'showAddInstallation')->name('installation.add');
    Route::get('/installations', 'showIndex')->name('installation.index');
    Route::get('/installations/{id}', 'showInstallation')->name('installation.show');

    Route::post('/installation/add', 'addInstallation')->name('installation.store');
    Route::post('/installation/update', 'updateInstallation')->name('installation.update');
    Route::post('/installation/{id}/devices', 'addInstallationDevice')->whereNumber('id')->name('installation.devices.store');
    Route::post('/installation/{installationId}/devices/{installationDeviceId}/delete', 'deleteInstallationDevice')->whereNumber(['installationId', 'installationDeviceId'])->name('installation.devices.delete');
    Route::post('/installation/{id}/delete', 'deleteInstallation')->name('installation.delete');
});

Route::middleware('auth')->controller(DeviceController::class)->group(function () {
    Route::get('/crud/lista', 'showIndex')->name('devices.index');
    Route::get('/crud/agregar', 'showAddDevice')->name('devices.add');
    Route::get('/crud/{id}', 'showDevice')->whereNumber('id')->name('devices.show');

    Route::post('/crud/agregar', 'addDevice')->name('devices.store');
    Route::post('/crud/update', 'updateDevice')->name('devices.update');
    Route::post('/crud/{id}/delete', 'deleteDevice')->whereNumber('id')->name('devices.delete');
});

Route::middleware('auth')->controller(Configcontroller::class)->group(function () {
    Route::get('/settings', 'showSettings')->name('user.settings');
    Route::post('/config/addCategory', 'addCategory')->name('add.category');
    Route::post('/config/addState', 'addState')->name('add.state');
    Route::post('/config/addDeviceType', 'addDeviceType')->name('add.type');
    Route::post('/config/addFeature', 'addFeature')->name('add.feature');
});

Route::middleware('auth')->controller(APIController::class)->group(function () {
    Route::get('/whatsapp', 'show')->name('show.whatsapp');
    Route::get('/api/conversations', [APIController::class, 'conversations']);
    Route::get('/api/conversations/{id}', [APIController::class, 'conversation']);
    Route::patch('/api/conversations/{id}/status', [APIController::class, 'updateStatus']);
    Route::post('/api/conversations/{id}/messages', [APIController::class, 'sendMessage']);
});

Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home', [
            'title' => 'Inicio',
        ]);
    })->name('home');

    Route::get('/crm/dashboard', function () {
        return Inertia::render('CRM/Dashboard', [
            'title' => 'Dashboard CRM',
        ]);
    })->name('crm.dashboard');

    Route::get('/crud/dashboard', function () {
        return Inertia::render('CRUD/Dashboard', [
            'title' => 'Dashboard CRUD',
        ]);
    })->name('crud.dashboard');
});
