<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses;

class Payload
{
    public Message $message;
    public Metadata $metadata;
    public Contact $contact;

    public function __construct(array $payload, int $messageIndex)
    {
        $messagePayload = $payload['messages'][$messageIndex];

        $contactPayload = $payload['contacts'][$messageIndex];
        $metadataPayload = $payload['metadata'];

        $context = null;
        if (isset($messagePayload['context']['id'])) {
            $context = new Context($messagePayload['context']['id']);
        }

        $this->message = new Message(
            $messagePayload['from'],
            $messagePayload['id'],
            $messagePayload['timestamp'],
            $messagePayload['type'],
            $context
        );

        $this->metadata = new Metadata(
            $metadataPayload['display_phone_number'],
            $metadataPayload['phone_number_id']
        );

        $this->contact = new Contact(
            $contactPayload['wa_id'],
            $contactPayload['profile']['name']
        );
    }

}
