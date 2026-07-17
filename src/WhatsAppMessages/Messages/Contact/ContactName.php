<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact;

class ContactName
{
    private string $formattedName;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $middleName = null;
    private ?string $suffix = null;
    private ?string $prefix = null;

    public function __construct(string $formattedName)
    {
        $this->formattedName = $formattedName;
    }

    public static function create(string $formattedName): ContactName
    {
        return new self($formattedName);
    }

    public function firstName(string $firstName): ContactName
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function lastName(string $lastName): ContactName
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function middleName(string $middleName): ContactName
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function suffix(string $suffix): ContactName
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function prefix(string $prefix): ContactName
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function toArray(): array
    {
        $name = [
            'formatted_name' => $this->formattedName,
        ];

        if ($this->firstName !== null) {
            $name['first_name'] = $this->firstName;
        }
        if ($this->lastName !== null) {
            $name['last_name'] = $this->lastName;
        }
        if ($this->middleName !== null) {
            $name['middle_name'] = $this->middleName;
        }
        if ($this->suffix !== null) {
            $name['suffix'] = $this->suffix;
        }
        if ($this->prefix !== null) {
            $name['prefix'] = $this->prefix;
        }

        return $name;
    }
}
