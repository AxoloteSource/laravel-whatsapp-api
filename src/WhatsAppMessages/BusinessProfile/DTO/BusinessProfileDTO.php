<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile\DTO;

class BusinessProfileDTO
{
    public string $about;
    public string $description;
    public string $email;
    public string $profilePictureUrl;
    public string $vertical;
    public array $websites;
    public ?string $address;

    public function __construct(array $data)
    {
        $this->about = $data['about'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->profilePictureUrl = $data['profile_picture_url'] ?? '';
        $this->vertical = $data['vertical'] ?? '';
        $this->websites = $data['websites'] ?? [];
        $this->address = $data['address'] ?? null;
    }
}
