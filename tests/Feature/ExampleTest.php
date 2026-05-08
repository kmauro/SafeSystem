<?php

use App\Domains\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertOk();
});
