<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Payload;

class ButtonPayload extends Payload
{
    private string $payload;
    private string $text;

    const TYPE_BUTTON_REPLY = 'button_reply';
    const TYPE_LIST_REPLY = 'list_reply';

    public function __construct(array $payload, int $messageIndex)
    {
        $button = $payload['messages'][$messageIndex]['button'];
        parent::__construct($payload, $messageIndex);

        $this->payload = $button['payload'];
        $this->text = $button['text'];
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

}
