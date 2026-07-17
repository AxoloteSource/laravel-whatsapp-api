<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\PhoneNumberInfo;

class WhatsAppPhoneNumber
{
    /**
     * Get phone number information
     *
     * @return PhoneNumberInfo
     */
    public static function info(): PhoneNumberInfo
    {
        return new PhoneNumberInfo();
    }
}
