<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Contact;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Context;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Message;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Metadata;
use Exception;

class Value
{
    public array $messages = [];
    public array $contacts = [];
    public array $statuses = [];
    public array $errors = [];
    public Metadata $metadata;

    public function __construct(array $value)
    {
        if (!isset($value['metadata'])) {
            throw new Exception("Webhook payload is missing 'metadata' field");
        }

        $metadataPayload = $value['metadata'];
        $this->metadata = new Metadata(
            $metadataPayload['display_phone_number'],
            $metadataPayload['phone_number_id']
        );

        if (isset($value['messages']) && is_array($value['messages'])) {
            foreach ($value['messages'] as $index => $messagePayload) {
                $context = null;
                if (isset($messagePayload['context']['id'])) {
                    $context = new Context($messagePayload['context']['id']);
                }

                $this->messages[] = new Message(
                    $messagePayload['from'],
                    $messagePayload['id'],
                    $messagePayload['timestamp'],
                    $messagePayload['type'],
                    $context
                );

                if (isset($value['contacts'][$index])) {
                    $contactPayload = $value['contacts'][$index];
                    $this->contacts[] = new Contact(
                        $contactPayload['wa_id'],
                        $contactPayload['profile']['name'] ?? ''
                    );
                }
            }
        }

        if (isset($value['statuses']) && is_array($value['statuses'])) {
            $this->statuses = $value['statuses'];
        }

        if (isset($value['errors']) && is_array($value['errors'])) {
            $this->errors = $value['errors'];
        }
    }

    public function hasMessages(): bool
    {
        return !empty($this->messages);
    }

    public function hasStatuses(): bool
    {
        return !empty($this->statuses);
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function firstMessage(): ?Message
    {
        return $this->messages[0] ?? null;
    }
}
