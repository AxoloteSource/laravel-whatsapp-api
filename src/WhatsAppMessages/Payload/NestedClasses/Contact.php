<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses;

class Contact
{

    /**
     * The customer's WhatsApp ID. A business can respond to a message using this ID.
     */
    private string $waId;

    /**
     * The customer’s name
     */
    private string $profileName;

    public function __construct(string $waId, string $profileName)
    {
        $this->profileName = $profileName;
        $this->waId = $waId;
    }

    /**
     * Return phone number without country code
     *
     * @return string
     */
    public function getWaId(): string
    {
        return $this->waId;
    }

    /**
     * Profile name
     *
     * @return string
     */
    public function getProfileName(): string
    {
        return $this->profileName;
    }

}
