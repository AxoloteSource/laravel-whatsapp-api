<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Location\LocationMessage;
use Exception;

class WhatsAppLocationMessageTest extends TestCase
{
    public function test_can_create_location_message()
    {
        $location = WhatsAppMessages::location('521234567890');
        $this->assertInstanceOf(LocationMessage::class, $location);
    }

    public function test_location_message_to_array_contains_latitude_and_longitude()
    {
        $location = WhatsAppMessages::location('521234567890')
            ->latitude(19.4326)
            ->longitude(-99.1332);

        $payload = $location->toArray();

        $this->assertEquals('location', $payload['type']);
        $this->assertEquals(19.4326, $payload['location']['latitude']);
        $this->assertEquals(-99.1332, $payload['location']['longitude']);
    }

    public function test_location_message_to_array_with_name_and_address()
    {
        $location = WhatsAppMessages::location('521234567890')
            ->latitude(19.4326)
            ->longitude(-99.1332)
            ->name('CDMX Centro')
            ->address('Plaza de la Constitución, Centro Histórico');

        $payload = $location->toArray();

        $this->assertEquals('location', $payload['type']);
        $this->assertEquals(19.4326, $payload['location']['latitude']);
        $this->assertEquals(-99.1332, $payload['location']['longitude']);
        $this->assertEquals('CDMX Centro', $payload['location']['name']);
        $this->assertEquals('Plaza de la Constitución, Centro Histórico', $payload['location']['address']);
    }

    public function test_location_message_to_array_omits_name_when_not_set()
    {
        $location = WhatsAppMessages::location('521234567890')
            ->latitude(40.7128)
            ->longitude(-74.0060)
            ->address('New York, NY');

        $payload = $location->toArray();

        $this->assertArrayHasKey('latitude', $payload['location']);
        $this->assertArrayHasKey('longitude', $payload['location']);
        $this->assertArrayHasKey('address', $payload['location']);
        $this->assertArrayNotHasKey('name', $payload['location']);
    }

    public function test_location_message_includes_recipient()
    {
        $location = WhatsAppMessages::location('521234567890')
            ->latitude(0.0)
            ->longitude(0.0);

        $payload = $location->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_location_message_throws_exception_without_latitude()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Latitude and longitude are required for location messages");

        $location = WhatsAppMessages::location('521234567890')
            ->longitude(-99.1332);

        $location->toArray();
    }

    public function test_location_message_throws_exception_without_longitude()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Latitude and longitude are required for location messages");

        $location = WhatsAppMessages::location('521234567890')
            ->latitude(19.4326);

        $location->toArray();
    }

    public function test_latitude_validation_rejects_out_of_range()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Latitude must be between -90 and 90");

        WhatsAppMessages::location('521234567890')->latitude(95.0);
    }

    public function test_latitude_validation_rejects_negative_out_of_range()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Latitude must be between -90 and 90");

        WhatsAppMessages::location('521234567890')->latitude(-91.0);
    }

    public function test_longitude_validation_rejects_out_of_range()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Longitude must be between -180 and 180");

        WhatsAppMessages::location('521234567890')->longitude(200.0);
    }

    public function test_longitude_validation_rejects_negative_out_of_range()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Longitude must be between -180 and 180");

        WhatsAppMessages::location('521234567890')->longitude(-200.0);
    }

    public function test_location_message_supports_fluent_chaining()
    {
        $location = WhatsAppMessages::location('521234567890')
            ->latitude(34.0522)
            ->longitude(-118.2437)
            ->name('Los Angeles')
            ->address('California, USA');

        $this->assertInstanceOf(LocationMessage::class, $location);
    }
}
