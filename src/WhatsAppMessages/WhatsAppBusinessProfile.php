<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile\BusinessProfileInfo;

class WhatsAppBusinessProfile
{
    /**
     * Get business profile information
     *
     * @return BusinessProfileInfo
     */
    public static function info(): BusinessProfileInfo
    {
        return new BusinessProfileInfo();
    }
}
