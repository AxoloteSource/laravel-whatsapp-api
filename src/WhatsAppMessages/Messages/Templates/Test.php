<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;

class Test extends WhatsAppBase
{
    public function __construct(string $to)
    {
        parent::__construct($to, MessageType::TEMPLATE);
    }

    protected function action(): array
    {
        return [
            'type' => MessageType::TEMPLATE,
            MessageType::TEMPLATE => [
                'name' => 'hello_world',
                'language' =>
                    [
                        'code' => 'en_US',
                    ],
            ],
        ];
    }
}
