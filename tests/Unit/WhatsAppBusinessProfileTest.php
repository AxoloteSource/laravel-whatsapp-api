<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppBusinessProfile;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile\BusinessProfileInfo;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile\DTO\BusinessProfileDTO;

class WhatsAppBusinessProfileTest extends TestCase
{
    public function test_can_get_business_profile_instance()
    {
        $profile = WhatsAppBusinessProfile::info();
        $this->assertInstanceOf(BusinessProfileInfo::class, $profile);
    }

    public function test_can_get_business_profile_with_fake_response()
    {
        WhatsAppMessages::fake();

        $result = WhatsAppBusinessProfile::info()->get();

        $this->assertInstanceOf(BusinessProfileDTO::class, $result);
        $this->assertEquals('About my business', $result->about);
        $this->assertEquals('This is a longer description of the business', $result->description);
        $this->assertEquals('contact@example.com', $result->email);
        $this->assertEquals('https://example.com/photo.jpg', $result->profilePictureUrl);
        $this->assertEquals('RETAIL', $result->vertical);
        $this->assertCount(2, $result->websites);
        $this->assertEquals('https://example.com', $result->websites[0]);
        $this->assertEquals('https://shop.example.com', $result->websites[1]);
        $this->assertEquals('123 Main St, Mexico City', $result->address);
    }

    public function test_returns_business_profile_dto_with_correct_property_types()
    {
        WhatsAppMessages::fake();

        $result = WhatsAppBusinessProfile::info()->get();

        $this->assertIsString($result->about);
        $this->assertIsString($result->description);
        $this->assertIsString($result->email);
        $this->assertIsString($result->profilePictureUrl);
        $this->assertIsString($result->vertical);
        $this->assertIsArray($result->websites);
    }
}
