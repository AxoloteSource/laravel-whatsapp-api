<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums;

class PhoneNumberFieldEnum
{
    const Id = 'id';
    const VerifiedName = 'verified_name';
    const QualityRating = 'quality_rating';
    const QualityScore = 'quality_score';
    const CodeVerificationStatus = 'code_verification_status';
    const DisplayPhoneNumber = 'display_phone_number';
    const PlatformType = 'platform_type';
    const Throughput = 'throughput';

    public static function all(): array
    {
        return [
            self::Id,
            self::VerifiedName,
            self::QualityRating,
            self::QualityScore,
            self::CodeVerificationStatus,
            self::DisplayPhoneNumber,
            self::PlatformType,
            self::Throughput,
        ];
    }
}
