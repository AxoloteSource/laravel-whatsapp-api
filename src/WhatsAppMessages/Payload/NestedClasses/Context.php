<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses;

class Context
{
    /**
     *  The message ID for the sent message for an inbound reply
     */
    private string $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
