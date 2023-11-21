<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects;

use Exception;

trait Body
{

    /**
     * Optional for type product. Required for other message types.
     * An object with the body of the message.
     * The body object contains the following field:
     * textstring – Required if body is present. The content of the message. Emojis and markdown are supported. Maximum length: 1024 characters.
     */
    private string $textBody;

    /**
     * Set to $textBody
     */
    public function body(string $text)
    {
        if (strlen($text) > 1024) {
            throw new Exception("Value must be <= 1024");
        }
        $this->textBody = $text;

        return $this;
    }

    /**
     * Get to $textBody
     */
    protected function getBody() : string
    {
        return $this->textBody;
    }
}
