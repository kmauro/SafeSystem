<?php

namespace App\Domains\API\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Domains\API\Services\WhatsappBotService;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;

class APIController extends Controller
{
    public function show(): InertiaResponse
    {
        return Inertia::render('Whatsapp/index',[
            'title' => 'Whatsapp BOT',
        ]);
    }

    public function conversations(Request $request, WhatsappBotService $bot)
    {
        return response()->json(
            $bot->conversations(
                $request->query('status'),
                $request->query('search')
            )
        );
    }

    public function conversation(string $id, WhatsappBotService $bot)
    {
        return response()->json(
            $bot->conversation($id)
        );
    }

    public function updateStatus(string $id, Request $request, WhatsappBotService $bot)
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        return response()->json(
            $bot->updateStatus($id, $validated['status'])
        );
    }

    public function sendMessage(string $id, Request $request, WhatsappBotService $bot)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        return response()->json(
            $bot->sendMessage($id, $validated['body'])
        );
    }
}
