<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses;

class Message
{
    /**
     * The customer's phone number who sent the message to the business.
     */
    private string $from;

    /**
     * The ID for the message that was received by the business.
     * You could use messages endpoint to mark this specific message as read.
     */
    private string $id;

    /**
     * The time when the WhatsApp server received the message from the customer.
     */
    private string $timestamp;

    /**
     * The type of message that has been received by the business that has subscribed to Webhooks.
     * Possible value can be one of the following:
     *
     * audio, button, document, text, image, interactive, order, sticker, unknown, video and
     * system – for customer number change messages
     */
    private string $type;

    /**
     * Included in the messages object when a user replies or interacts with one of your messages.
     */
    private ?Context $context;

    public function __construct(string $from, string $id, string $timestamp, string $type, Context $context =  null)
    {
        $this->from = $from;
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->type = $type;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }


}
