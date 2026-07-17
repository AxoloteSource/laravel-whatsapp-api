<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Exception;

class WebhookHandler
{
    private array $payload;
    private int $messageIndex;

    public function __construct(array $payload, int $messageIndex = 0)
    {
        $this->payload = $payload;
        $this->messageIndex = $messageIndex;
    }

    public static function fromRequest(array $payload, int $messageIndex = 0): self
    {
        return new self($payload, $messageIndex);
    }

    public function value(): Value
    {
        return new Value($this->payload);
    }

    public function text(): TextPayload
    {
        $this->ensureType('text');
        return new TextPayload($this->payload, $this->messageIndex);
    }

    public function button(): ButtonPayload
    {
        $this->ensureType('button');
        return new ButtonPayload($this->payload, $this->messageIndex);
    }

    public function interactive(): InteractivePayload
    {
        $this->ensureType('interactive');
        return new InteractivePayload($this->payload, $this->messageIndex);
    }

    public function statuses(): array
    {
        if (!isset($this->payload['statuses']) || !is_array($this->payload['statuses'])) {
            return [];
        }

        return array_map(function ($status) {
            return new StatusPayload($status);
        }, $this->payload['statuses']);
    }

    public function firstStatus(): ?StatusPayload
    {
        $statuses = $this->statuses();
        return $statuses[0] ?? null;
    }

    public function getType(): ?string
    {
        return $this->payload['messages'][$this->messageIndex]['type'] ?? null;
    }

    private function ensureType(string $expected): void
    {
        $type = $this->getType();
        if ($type !== $expected) {
            throw new Exception("Expected message type '$expected', got '" . ($type ?? 'null') . "'");
        }
    }
}
