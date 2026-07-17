<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact;

class ContactPhone
{
    public const TYPE_CELL = 'CELL';
    public const TYPE_MAIN = 'MAIN';
    public const TYPE_IPHONE = 'IPHONE';
    public const TYPE_HOME = 'HOME';
    public const TYPE_WORK = 'WORK';

    private string $phone;
    private ?string $type = null;

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public static function create(string $phone): ContactPhone
    {
        return new self($phone);
    }

    public function type(string $type): ContactPhone
    {
        $this->type = $type;

        return $this;
    }

    public function toArray(): array
    {
        $entry = [
            'phone' => $this->phone,
        ];

        if ($this->type !== null) {
            $entry['type'] = $this->type;
        }

        return $entry;
    }
}
