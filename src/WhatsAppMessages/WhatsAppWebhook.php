<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\WebhookHandler;
use Exception;

class WhatsAppWebhook
{
    /**
     * Verify webhook subscription from Meta
     * Returns the challenge to respond to GET request, or throws if invalid
     *
     * @throws Exception
     */
    public static function verify(string $mode, string $token, string $challenge): string
    {
        $verifyToken = config('laravel-whatsapp-api.verify_token');

        if ($mode !== 'subscribe') {
            throw new Exception("Invalid mode: expected 'subscribe', got '$mode'");
        }

        if ($token !== $verifyToken) {
            throw new Exception("Invalid verify token");
        }

        return $challenge;
    }

    public static function handle(array $payload, int $messageIndex = 0): WebhookHandler
    {
        return WebhookHandler::fromRequest($payload, $messageIndex);
    }
}
