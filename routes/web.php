<?php

use App\Domains\Users\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->controller(UserController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.store');

    Route::get('/signup', 'showSignup')->name('signup');
    Route::post('/signup', 'signup')->name('signup.store');

});

Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/profile', 'showProfile')->name('profile');
    Route::get('/users', 'showUsers')->name('users.index');
    Route::get('/users/{id}', 'showUser')->name('users.show');
    Route::post('/profile', 'updateProfile')->name('profile.update');
    Route::post('/profile/updatePassword', 'updatePassword')->name('profile.updatePassword');
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
