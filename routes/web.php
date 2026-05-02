<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'title' => 'Welcome to Laravel with Inertia.js',
    ]);
});


Route::get('/users', function () {
    return Inertia::render('Users', [
        'title' => 'User List',
        'users' => [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
            ['id' => 3, 'name' => 'Charlie'],
        ],
    ]);
});

Route::get('/products', function (){
    return Inertia::render('Products', [
        'title' => 'Product List',
    ]);
});
