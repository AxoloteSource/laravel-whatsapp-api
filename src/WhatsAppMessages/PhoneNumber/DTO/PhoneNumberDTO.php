<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\DTO;

class PhoneNumberDTO
{
    public string $id;
    public string $verifiedName;
    public string $qualityRating;
    public string $qualityScore;
    public string $codeVerificationStatus;
    public string $displayPhoneNumber;
    public string $platformType;
    public array $throughput;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->verifiedName = $data['verified_name'] ?? '';
        $this->qualityRating = $data['quality_rating'] ?? '';
        $this->qualityScore = $data['quality_score']['score'] ?? '';
        $this->codeVerificationStatus = $data['code_verification_status'] ?? '';
        $this->displayPhoneNumber = $data['display_phone_number'] ?? '';
        $this->platformType = $data['platform_type'] ?? '';
        $this->throughput = $data['throughput'] ?? [];
    }
}
