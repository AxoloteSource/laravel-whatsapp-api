<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Reaction;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Exception;

class ReactionMessage extends WhatsAppBase
{
    private ?string $messageId = null;
    private ?string $emoji = null;

    public function __construct(string $to)
    {
        parent::__construct($to, MessageType::REACTION);
    }

    public function messageId(string $messageId): ReactionMessage
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function emoji(string $emoji): ReactionMessage
    {
        $this->emoji = $emoji;

        return $this;
    }

    protected function action(): array
    {
        if ($this->messageId === null) {
            throw new Exception("Message id is required for reaction messages");
        }

        if ($this->emoji === null) {
            throw new Exception("Emoji is required for reaction messages");
        }

        return [
            'type' => 'reaction',
            'reaction' => [
                'message_id' => $this->messageId,
                'emoji' => $this->emoji,
            ],
        ];
    }
}
