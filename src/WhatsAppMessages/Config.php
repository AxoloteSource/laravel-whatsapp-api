<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

trait Config
{
    private string $phoneNumberID;
    private string $to;
    private string $bearer;
    private string $baseUrl;

    private function initialize()
    {
        $this->baseUrl = config('laravel-whatsapp-api.api');
        $this->phoneNumberID = config('laravel-whatsapp-api.phone_number_id');
        $this->bearer = config('laravel-whatsapp-api.bearer');
        if (config('laravel-whatsapp-api.test_mode')) {
            $this->to = config('laravel-whatsapp-api.test_number');
        }
    }
}
