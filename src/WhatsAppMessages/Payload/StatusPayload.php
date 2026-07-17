<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

class StatusPayload
{
    public const STATUS_SENT = 'sent';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_READ = 'read';
    public const STATUS_FAILED = 'failed';

    private string $id;
    private string $status;
    private string $recipientId;
    private ?string $timestamp;
    private ?array $conversation;
    private ?array $pricing;
    private ?array $errors;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->recipientId = $data['recipient_id'] ?? '';
        $this->timestamp = $data['timestamp'] ?? null;
        $this->conversation = $data['conversation'] ?? null;
        $this->pricing = $data['pricing'] ?? null;
        $this->errors = $data['errors'] ?? null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function getConversation(): ?array
    {
        return $this->conversation;
    }

    public function getPricing(): ?array
    {
        return $this->pricing;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }
}
