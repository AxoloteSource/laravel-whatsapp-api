<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Location;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Exception;

class LocationMessage extends WhatsAppBase
{
    private ?float $latitude = null;
    private ?float $longitude = null;
    private ?string $name = null;
    private ?string $address = null;

    public function __construct(string $to)
    {
        parent::__construct($to, MessageType::LOCATION);
    }

    public function latitude(float $latitude): LocationMessage
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new Exception("Latitude must be between -90 and 90");
        }
        $this->latitude = $latitude;

        return $this;
    }

    public function longitude(float $longitude): LocationMessage
    {
        if ($longitude < -180 || $longitude > 180) {
            throw new Exception("Longitude must be between -180 and 180");
        }
        $this->longitude = $longitude;

        return $this;
    }

    public function name(string $name): LocationMessage
    {
        $this->name = $name;

        return $this;
    }

    public function address(string $address): LocationMessage
    {
        $this->address = $address;

        return $this;
    }

    protected function action(): array
    {
        if ($this->latitude === null || $this->longitude === null) {
            throw new Exception("Latitude and longitude are required for location messages");
        }

        $location = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        if ($this->name !== null) {
            $location['name'] = $this->name;
        }

        if ($this->address !== null) {
            $location['address'] = $this->address;
        }

        return [
            'type' => 'location',
            'location' => $location,
        ];
    }
}
