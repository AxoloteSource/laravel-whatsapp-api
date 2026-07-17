<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\DTO;

class PhoneNumberDTO
{
    public string $id;
    public string $verifiedName;
    public string $qualityRating;
    public string $qualityScore;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->verifiedName = $data['verified_name'] ?? '';
        $this->qualityRating = $data['quality_rating'] ?? '';
        $this->qualityScore = $data['quality_score']['score'] ?? '';
    }
}
