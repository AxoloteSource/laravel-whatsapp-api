<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums;

class TemplateFieldEnum
{
    const Id = 'id';
    const Name = 'name';
    const Status = 'status';
    const Category = 'category';
    const Language = 'language';
    const Components = 'components';
    const LastUpdatedTime = 'last_updated_time';
    const QualityScore = 'quality_score';
    const RejectedReason = 'rejected_reason';

    public static function all(): array
    {
        return [
            self::Id,
            self::Name,
            self::Status,
            self::Category,
            self::Language,
            self::Components,
            self::LastUpdatedTime,
            self::QualityScore,
            self::RejectedReason,
        ];
    }
}
