<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Text;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;

class TextMessage extends WhatsAppBase
{
    use Body;

    private bool $previewUrl;

    public function __construct(string $to, bool $previewUrl = true)
    {
        parent::__construct($to, MessageType::TEXT);
        $this->previewUrl = $previewUrl;
    }

    protected function action(): array
    {
        return [
            'type' => 'text',
            'text' => [
                'preview_url' => $this->previewUrl,
                'body' => $this->getBody(),
            ]
        ];
    }
}
