<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\Contact;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\ContactMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\ContactName;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\ContactPhone;
use Exception;

class WhatsAppContactMessageTest extends TestCase
{
    public function test_can_create_contact_message()
    {
        $contact = WhatsAppMessages::contact('521234567890');
        $this->assertInstanceOf(ContactMessage::class, $contact);
    }

    public function test_contact_name_to_array_contains_formatted_name()
    {
        $name = ContactName::create('John Doe');
        $this->assertEquals(['formatted_name' => 'John Doe'], $name->toArray());
    }

    public function test_contact_name_to_array_with_all_fields()
    {
        $name = ContactName::create('Mr. John A. Doe Jr.')
            ->prefix('Mr.')
            ->firstName('John')
            ->middleName('Andrew')
            ->lastName('Doe')
            ->suffix('Jr.');

        $array = $name->toArray();

        $this->assertEquals('Mr. John A. Doe Jr.', $array['formatted_name']);
        $this->assertEquals('Mr.', $array['prefix']);
        $this->assertEquals('John', $array['first_name']);
        $this->assertEquals('Andrew', $array['middle_name']);
        $this->assertEquals('Doe', $array['last_name']);
        $this->assertEquals('Jr.', $array['suffix']);
    }

    public function test_contact_phone_to_array_without_type()
    {
        $phone = ContactPhone::create('+521234567890');
        $this->assertEquals(['phone' => '+521234567890'], $phone->toArray());
    }

    public function test_contact_phone_to_array_with_type()
    {
        $phone = ContactPhone::create('+521234567890')->type(ContactPhone::TYPE_CELL);
        $this->assertEquals(['phone' => '+521234567890', 'type' => 'CELL'], $phone->toArray());
    }

    public function test_contact_to_array_with_only_name()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'));

        $array = $contact->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertEquals('Jane Smith', $array['name']['formatted_name']);
        $this->assertArrayNotHasKey('phones', $array);
        $this->assertArrayNotHasKey('emails', $array);
    }

    public function test_contact_to_array_with_phones()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->addPhone(ContactPhone::create('+521111111111')->type(ContactPhone::TYPE_CELL))
            ->addPhone(ContactPhone::create('+522222222222')->type(ContactPhone::TYPE_WORK));

        $array = $contact->toArray();

        $this->assertCount(2, $array['phones']);
        $this->assertEquals('+521111111111', $array['phones'][0]['phone']);
        $this->assertEquals('CELL', $array['phones'][0]['type']);
        $this->assertEquals('+522222222222', $array['phones'][1]['phone']);
        $this->assertEquals('WORK', $array['phones'][1]['type']);
    }

    public function test_contact_to_array_with_emails()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->addEmail('jane@example.com', 'WORK')
            ->addEmail('jane@personal.com');

        $array = $contact->toArray();

        $this->assertCount(2, $array['emails']);
        $this->assertEquals('jane@example.com', $array['emails'][0]['email']);
        $this->assertEquals('WORK', $array['emails'][0]['type']);
        $this->assertEquals('jane@personal.com', $array['emails'][1]['email']);
        $this->assertArrayNotHasKey('type', $array['emails'][1]);
    }

    public function test_contact_to_array_with_urls()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->addUrl('https://example.com', 'WORK');

        $array = $contact->toArray();

        $this->assertEquals('https://example.com', $array['urls'][0]['url']);
        $this->assertEquals('WORK', $array['urls'][0]['type']);
    }

    public function test_contact_to_array_with_orgs()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->addOrg('Acme Corp', 'Engineering', 'CTO');

        $array = $contact->toArray();

        $this->assertEquals('Acme Corp', $array['orgs'][0]['company']);
        $this->assertEquals('Engineering', $array['orgs'][0]['department']);
        $this->assertEquals('CTO', $array['orgs'][0]['title']);
    }

    public function test_contact_to_array_with_addresses()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->addAddress('123 Main St', 'Mexico City', 'CDMX', '06000', 'Mexico', 'MX', 'WORK');

        $array = $contact->toArray();

        $this->assertEquals('123 Main St', $array['addresses'][0]['street']);
        $this->assertEquals('Mexico City', $array['addresses'][0]['city']);
        $this->assertEquals('CDMX', $array['addresses'][0]['state']);
        $this->assertEquals('06000', $array['addresses'][0]['zip']);
        $this->assertEquals('Mexico', $array['addresses'][0]['country']);
        $this->assertEquals('MX', $array['addresses'][0]['country_code']);
        $this->assertEquals('WORK', $array['addresses'][0]['type']);
    }

    public function test_contact_birthday_accepts_valid_format()
    {
        $contact = Contact::create(ContactName::create('Jane Smith'))
            ->birthday('1990-05-15');

        $array = $contact->toArray();

        $this->assertEquals('1990-05-15', $array['birthday']);
    }

    public function test_contact_birthday_rejects_invalid_format()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Birthday must be in YYYY-MM-DD format");

        Contact::create(ContactName::create('Jane Smith'))->birthday('15/05/1990');
    }

    public function test_contact_message_to_array_with_one_contact()
    {
        $contact = Contact::create(ContactName::create('John Doe'))
            ->addPhone(ContactPhone::create('+521234567890')->type(ContactPhone::TYPE_CELL));

        $msg = WhatsAppMessages::contact('521234567890')->addContact($contact);

        $payload = $msg->toArray();

        $this->assertEquals('contacts', $payload['type']);
        $this->assertCount(1, $payload['contacts']);
        $this->assertEquals('John Doe', $payload['contacts'][0]['name']['formatted_name']);
        $this->assertEquals('+521234567890', $payload['contacts'][0]['phones'][0]['phone']);
    }

    public function test_contact_message_to_array_with_multiple_contacts()
    {
        $contact1 = Contact::create(ContactName::create('John Doe'));
        $contact2 = Contact::create(ContactName::create('Jane Smith'));

        $msg = WhatsAppMessages::contact('521234567890')
            ->addContact($contact1)
            ->addContact($contact2);

        $payload = $msg->toArray();

        $this->assertCount(2, $payload['contacts']);
        $this->assertEquals('John Doe', $payload['contacts'][0]['name']['formatted_name']);
        $this->assertEquals('Jane Smith', $payload['contacts'][1]['name']['formatted_name']);
    }

    public function test_contact_message_throws_exception_without_contacts()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("At least one contact is required for contact messages");

        WhatsAppMessages::contact('521234567890')->toArray();
    }

    public function test_contact_message_includes_recipient()
    {
        $contact = Contact::create(ContactName::create('John Doe'));

        $msg = WhatsAppMessages::contact('521234567890')->addContact($contact);
        $payload = $msg->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_contact_message_supports_fluent_chaining()
    {
        $msg = WhatsAppMessages::contact('521234567890')
            ->addContact(
                Contact::create(ContactName::create('John Doe'))
                    ->addPhone(ContactPhone::create('+521234567890'))
            )
            ->addContact(Contact::create(ContactName::create('Jane Smith')));

        $this->assertInstanceOf(ContactMessage::class, $msg);
    }
}
