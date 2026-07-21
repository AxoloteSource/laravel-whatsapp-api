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
        $this->assertEquals('GREEN', $result->qualityScore);
        $this->assertEquals('EXPIRED', $result->codeVerificationStatus);
        $this->assertEquals('+52 1 33 3781 5545', $result->displayPhoneNumber);
        $this->assertEquals('CLOUD_API', $result->platformType);
        $this->assertEquals(['level' => 'STANDARD'], $result->throughput);
    }

    public function test_returns_phone_number_dto_with_correct_property_types()
    {
        WhatsAppMessages::fake();

        $result = WhatsAppPhoneNumber::info()->get();

        $this->assertIsString($result->id);
        $this->assertIsString($result->verifiedName);
        $this->assertIsString($result->qualityRating);
        $this->assertIsString($result->qualityScore);
        $this->assertIsString($result->codeVerificationStatus);
        $this->assertIsString($result->displayPhoneNumber);
        $this->assertIsString($result->platformType);
        $this->assertIsArray($result->throughput);
    }

    public function test_can_select_fields()
    {
        $info = WhatsAppPhoneNumber::info();
        $fields = ['id', 'verified_name', 'quality_rating'];
        $info->select($fields);

        $reflection = new \ReflectionClass($info);
        $property = $reflection->getProperty('fields');
        $property->setAccessible(true);

        $this->assertEquals($fields, $property->getValue($info));
    }

    public function test_throws_exception_for_invalid_field_in_select()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid field: invalid_field");

        WhatsAppPhoneNumber::info()->select(['id', 'invalid_field']);
    }
}
