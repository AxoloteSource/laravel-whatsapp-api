<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Exception;

class ContactMessage extends WhatsAppBase
{
    private array $contacts = [];

    public function __construct(string $to)
    {
        parent::__construct($to, MessageType::CONTACTS);
    }

    public function addContact(Contact $contact): ContactMessage
    {
        $this->contacts[] = $contact->toArray();

        return $this;
    }

    protected function action(): array
    {
        if (empty($this->contacts)) {
            throw new Exception("At least one contact is required for contact messages");
        }

        return [
            'type' => 'contacts',
            'contacts' => $this->contacts,
        ];
    }
}
