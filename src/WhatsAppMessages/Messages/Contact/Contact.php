<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact;

use Exception;

class Contact
{
    private ContactName $name;
    private array $phones = [];
    private array $emails = [];
    private array $urls = [];
    private array $orgs = [];
    private array $addresses = [];
    private ?string $birthday = null;

    public function __construct(ContactName $name)
    {
        $this->name = $name;
    }

    public static function create(ContactName $name): Contact
    {
        return new self($name);
    }

    public function addPhone(ContactPhone $phone): Contact
    {
        $this->phones[] = $phone->toArray();

        return $this;
    }

    public function addEmail(string $email, string $type = null): Contact
    {
        $entry = ['email' => $email];
        if ($type !== null) {
            $entry['type'] = $type;
        }
        $this->emails[] = $entry;

        return $this;
    }

    public function addUrl(string $url, string $type = null): Contact
    {
        $entry = ['url' => $url];
        if ($type !== null) {
            $entry['type'] = $type;
        }
        $this->urls[] = $entry;

        return $this;
    }

    public function addOrg(string $company = null, string $department = null, string $title = null): Contact
    {
        $org = [];
        if ($company !== null) {
            $org['company'] = $company;
        }
        if ($department !== null) {
            $org['department'] = $department;
        }
        if ($title !== null) {
            $org['title'] = $title;
        }
        $this->orgs[] = $org;

        return $this;
    }

    public function addAddress(string $street = null, string $city = null, string $state = null, string $zip = null, string $country = null, string $countryCode = null, string $type = null): Contact
    {
        $address = [];
        if ($street !== null) {
            $address['street'] = $street;
        }
        if ($city !== null) {
            $address['city'] = $city;
        }
        if ($state !== null) {
            $address['state'] = $state;
        }
        if ($zip !== null) {
            $address['zip'] = $zip;
        }
        if ($country !== null) {
            $address['country'] = $country;
        }
        if ($countryCode !== null) {
            $address['country_code'] = $countryCode;
        }
        if ($type !== null) {
            $address['type'] = $type;
        }
        $this->addresses[] = $address;

        return $this;
    }

    public function birthday(string $birthday): Contact
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
            throw new Exception("Birthday must be in YYYY-MM-DD format");
        }
        $this->birthday = $birthday;

        return $this;
    }

    public function toArray(): array
    {
        $contact = [
            'name' => $this->name->toArray(),
        ];

        if (!empty($this->phones)) {
            $contact['phones'] = $this->phones;
        }
        if (!empty($this->emails)) {
            $contact['emails'] = $this->emails;
        }
        if (!empty($this->urls)) {
            $contact['urls'] = $this->urls;
        }
        if (!empty($this->orgs)) {
            $contact['orgs'] = $this->orgs;
        }
        if (!empty($this->addresses)) {
            $contact['addresses'] = $this->addresses;
        }
        if ($this->birthday !== null) {
            $contact['birthday'] = $this->birthday;
        }

        return $contact;
    }
}
