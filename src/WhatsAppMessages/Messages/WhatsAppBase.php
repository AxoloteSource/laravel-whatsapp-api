<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages;

use Illuminate\Support\Str;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Http;

abstract class WhatsAppBase
{
    use Config;
    private string $messageType;

    abstract protected function action(): array;

    public function __construct(string $to, string $messageType = MessageType::INTERACTIVE)
    {
        $this->initialize();
        $this->messageType = $messageType;
        $this->to = config('laravel-whatsapp-api.test_mode') ? config('laravel-whatsapp-api.test_number') : $to;

    }

    public function send($to = null)
    {
        if (WhatsAppMessages::isFake()) {
            $this->initializeFakeResponse();
        }

        if ($to !== null) {
            $this->to = config('laravel-whatsapp-api.test_mode') ? config('laravel-whatsapp-api.test_number') : $to;
        }

        return Http::withHeaders($this->headers())->post(
            "$this->baseUrl$this->phoneNumberID/messages",
            $this->data()
        );
    }

    public function toArray() : array
    {
        return $this->data();
    }

    private function data() : array
    {
        return array_merge([
            'messaging_product' => 'whatsapp',
            "recipient_type" => "individual",
            'to' => $this->to,
        ], $this->action());
    }

    private function headers() : array
    {
        return [
            'Authorization' => "Bearer $this->bearer",
            'Content-Type' => "application/json"
        ];
    }

    private function initializeFakeResponse()
    {
        $prefix = 'wamid';
        $randomId = Str::random(16);
        $timestamp = now()->timestamp;

        Http::fake([
            '*' => Http::response([
                'test' => true,
                'messaging_product' => 'whatsapp',
                'contacts' => [
                    [
                        'input' => $this->data()['to'],
                        'wa_id' => $this->data()['to'],
                    ],
                    'messages' => [
                        [
                            'id' => "$prefix.".base64_encode("HBgN{$timestamp}NBUCABEYEjQx{$randomId}MzdBRQA="),
                            'message_status' => 'accepted',
                        ],
                    ],
                ],
            ], 200, $this->headers()),
        ]);
    }
}
