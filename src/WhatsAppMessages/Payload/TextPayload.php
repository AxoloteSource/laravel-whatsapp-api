<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Payload;

class TextPayload extends Payload
{
    private string $text;

    public function __construct($payload, $key)
    {
        parent::__construct($payload, $key);
        $this->text = $payload['messages'][0]['text']['body'];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
