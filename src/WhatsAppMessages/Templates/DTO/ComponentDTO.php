<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\DTO;

class ComponentDTO
{
    public string $type;
    public ?string $format;
    public ?string $text;
    public array $data;

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->format = $data['format'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->data = $data;
    }
}
