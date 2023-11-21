<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses;

class Metadata
{
    private string $displayPhoneNumber;
    private string $phoneNumberId;

    public function __construct(string $displayPhoneNumber, string $phoneNumberId)
    {
        $this->displayPhoneNumber = $displayPhoneNumber;
        $this->phoneNumberId = $phoneNumberId;
    }

    /**
     * @return string
     */
    public function getDisplayPhoneNumber(): string
    {
        return $this->displayPhoneNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumberId(): string
    {
        return $this->phoneNumberId;
    }

}
