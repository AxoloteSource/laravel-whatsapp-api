<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects;

use Exception;

trait Footer
{

    /**
     * Optional. An object with the footer of the message.
     * The footer object contains the following field:
     * textstring – Required if footer is present. The footer content. Emojis, markdown, and links are supported. Maximum length: 60 characters.
     */
    private $textFooter;

    /**
     * @param string $text
     * @return $this
     * @throws Exception
     */
    public function footer(string $text)
    {
        if (strlen($text) > 60) {
            throw new Exception("Value must be <= 60");
        }
        $this->textFooter = $text;

        return $this;
    }

    protected function getFooter(): ?string
    {
        return $this->textFooter;
    }

    protected function hasFooter(): bool
    {
        return $this->getFooter() != null;
    }

}
