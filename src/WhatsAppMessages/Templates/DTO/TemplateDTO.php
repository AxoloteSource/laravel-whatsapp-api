<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\DTO;

class TemplateDTO
{
    public string $name;
    public string $status;
    public string $category;
    public string $language;
    public array $components;
    public string $id;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->language = $data['language'] ?? '';
        $this->components = array_map(function ($component) {
            return new ComponentDTO($component);
        }, $data['components'] ?? []);
        $this->id = $data['id'] ?? '';
    }
}
