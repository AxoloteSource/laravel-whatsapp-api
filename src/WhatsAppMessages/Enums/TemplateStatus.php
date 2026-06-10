<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums;

class TemplateStatus
{
    const APPROVED = 'APPROVED';
    const PENDING = 'PENDING';
    const REJECTED = 'REJECTED';
    const DELETED = 'DELETED';
    const DISABLED = 'DISABLED';
    const IN_REVIEW = 'IN_REVIEW';

    public static function all(): array
    {
        return [
            self::APPROVED,
            self::PENDING,
            self::REJECTED,
            self::DELETED,
            self::DISABLED,
            self::IN_REVIEW,
        ];
    }
}
