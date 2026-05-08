<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'title' => 'Welcome to Laravel with Inertia.js',
    ]);
});


Route::get('/crm/dashboard', function () {
    return Inertia::render('CRM/Dashboard', [
        'title' => 'Dashboard CRM',
    ]);
});

Route::get('/crud/dashboard', function (){
    return Inertia::render('CRUD/Dashboard', [
        'title' => 'Dashboard CRUD',
    ]);
});
