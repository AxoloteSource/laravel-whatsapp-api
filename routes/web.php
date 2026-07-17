<?php

use Illuminate\Support\Facades\Route;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppWebhook;

$webhookPath = config('laravel-whatsapp-api.webhook_path', 'whatsapp/webhook');

Route::get($webhookPath, function (\Illuminate\Http\Request $request) {
    if (config('laravel-whatsapp-api.disable_hook')) {
        return response('', 200);
    }

    try {
        $challenge = WhatsAppWebhook::verify(
            $request->query('hub_mode'),
            $request->query('hub_verify_token'),
            $request->query('hub_challenge')
        );
        return response($challenge, 200);
    } catch (\Exception $e) {
        return response($e->getMessage(), 403);
    }
});

Route::post($webhookPath, function (\Illuminate\Http\Request $request) {
    if (config('laravel-whatsapp-api.disable_hook')) {
        return response('', 200);
    }

    $payload = $request->all();
    $handler = WhatsAppWebhook::handle($payload);

    return response('', 200);
});
