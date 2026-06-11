<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums;

class TemplateCategory
{
    const MARKETING = 'MARKETING';
    const UTILITY = 'UTILITY';
    const AUTHENTICATION = 'AUTHENTICATION';

    public static function all(): array
    {
        return [
            self::MARKETING,
            self::UTILITY,
            self::AUTHENTICATION,
        ];
    }
}
