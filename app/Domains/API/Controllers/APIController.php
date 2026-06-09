<?php

namespace App\Domains\API\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class APIController extends Controller
{
    public function show(): InertiaResponse
    {
        return Inertia::render('Whatsapp/index',[
            'title' => 'Whatsapp BOT',
        ]);
    }
}
