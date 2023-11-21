<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Contact;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Context;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Message;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Metadata;

class Value
{
    public array $messages = [];
    public Metadata $metadata;
    public Contact $contact;

    //TODO CREATE CLASS
    //public Errors $errors
    //public Statuses $statuses;

    public function __construct(array $value)
    {
       /* $messages = collect([]);
        foreach ($value['messages'] as $message) {
            $contextId = null;
            if (isset($message['context']['id'])) {
                $contextId = new Context($message['context']['id']);
            }

            array_push($this->messages, new Message(
                $message['from'],
                $message['id'],
                $message['timestamp'],
                $message['type'],
                $contextId
            ));
        }

        //$messagePayload = ;

        //$contactPayload = $payload['contacts'][$messageIndex];
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
        );*/
    }


}
