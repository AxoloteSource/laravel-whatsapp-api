<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppPhoneNumber;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\PhoneNumberInfo;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\DTO\PhoneNumberDTO;

class WhatsAppPhoneNumberTest extends TestCase
{
    public function test_can_get_phone_number_info_instance()
    {
        $info = WhatsAppPhoneNumber::info();
        $this->assertInstanceOf(PhoneNumberInfo::class, $info);
    }

    public function test_can_get_phone_number_info_with_fake_response()
    {
        WhatsAppMessages::fake();

        $result = WhatsAppPhoneNumber::info()->get();

        $this->assertInstanceOf(PhoneNumberDTO::class, $result);
        $this->assertEquals('123456789', $result->id);
        $this->assertEquals('Mi Empresa', $result->verifiedName);
        $this->assertEquals('GREEN', $result->qualityRating);
        $this->assertEquals(0, $result->qualityScore);
        $this->assertEquals('TIER_1000', $result->messagingLimitTier);
    }

    public function test_returns_phone_number_dto_with_default_values()
    {
        WhatsAppMessages::fake();

        $result = WhatsAppPhoneNumber::info()->get();

        $this->assertIsString($result->id);
        $this->assertIsString($result->verifiedName);
        $this->assertIsString($result->qualityRating);
        $this->assertIsInt($result->qualityScore);
        $this->assertIsString($result->messagingLimitTier);
    }
}
