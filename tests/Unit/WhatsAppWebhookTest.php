<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\Value;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\StatusPayload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\WebhookHandler;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\TextPayload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\ButtonPayload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\InteractivePayload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppWebhook;
use Exception;

class WhatsAppWebhookTest extends TestCase
{
    private function textPayload(): array
    {
        return [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => 'WHATSAPP_BUSINESS_ACCOUNT_ID',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '15551234567',
                                    'phone_number_id' => '123456789',
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '521234567890',
                                    ],
                                ],
                                'messages' => [
                                    [
                                        'from' => '521234567890',
                                        'id' => 'wamid.ABC123',
                                        'timestamp' => '1700000000',
                                        'text' => ['body' => 'Hello, world!'],
                                        'type' => 'text',
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function buttonPayload(): array
    {
        return [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => 'WHATSAPP_BUSINESS_ACCOUNT_ID',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '15551234567',
                                    'phone_number_id' => '123456789',
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '521234567890',
                                    ],
                                ],
                                'messages' => [
                                    [
                                        'from' => '521234567890',
                                        'id' => 'wamid.BUTTON123',
                                        'timestamp' => '1700000001',
                                        'type' => 'button',
                                        'button' => [
                                            'text' => 'Yes, confirm',
                                            'payload' => 'confirm-yes',
                                        ],
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function interactivePayload(): array
    {
        return [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => 'WHATSAPP_BUSINESS_ACCOUNT_ID',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '15551234567',
                                    'phone_number_id' => '123456789',
                                ],
                                'contacts' => [
                                    [
                                        'profile' => ['name' => 'John Doe'],
                                        'wa_id' => '521234567890',
                                    ],
                                ],
                                'messages' => [
                                    [
                                        'from' => '521234567890',
                                        'id' => 'wamid.INTERACTIVE123',
                                        'timestamp' => '1700000002',
                                        'type' => 'interactive',
                                        'interactive' => [
                                            'type' => 'button_reply',
                                            'button_reply' => [
                                                'id' => 'btn-1',
                                                'title' => 'First option',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function statusPayload(): array
    {
        return [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => 'WHATSAPP_BUSINESS_ACCOUNT_ID',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '15551234567',
                                    'phone_number_id' => '123456789',
                                ],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.STATUS123',
                                        'status' => 'delivered',
                                        'recipient_id' => '521234567890',
                                        'timestamp' => '1700000010',
                                    ],
                                ],
                            ],
                            'field' => 'messages',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function test_value_parses_text_message()
    {
        $handler = new WebhookHandler($this->textPayload()['entry'][0]['changes'][0]['value']);
        $value = $handler->value();

        $this->assertCount(1, $value->messages);
        $this->assertEquals('521234567890', $value->messages[0]->getFrom());
        $this->assertEquals('wamid.ABC123', $value->messages[0]->getId());
        $this->assertEquals('text', $value->messages[0]->getType());
        $this->assertTrue($value->hasMessages());
    }

    public function test_value_throws_exception_without_metadata()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("missing 'metadata' field");

        new Value(['messages' => []]);
    }

    public function test_value_parses_statuses()
    {
        $handler = new WebhookHandler($this->statusPayload()['entry'][0]['changes'][0]['value']);
        $value = $handler->value();

        $this->assertTrue($value->hasStatuses());
        $this->assertCount(1, $value->statuses);
        $this->assertEquals('delivered', $value->statuses[0]['status']);
    }

    public function test_value_metadata_fields()
    {
        $handler = new WebhookHandler($this->textPayload()['entry'][0]['changes'][0]['value']);
        $value = $handler->value();

        $this->assertEquals('15551234567', $value->metadata->getDisplayPhoneNumber());
        $this->assertEquals('123456789', $value->metadata->getPhoneNumberId());
    }

    public function test_handler_text_returns_text_payload()
    {
        $handler = new WebhookHandler($this->textPayload()['entry'][0]['changes'][0]['value']);
        $text = $handler->text();

        $this->assertInstanceOf(TextPayload::class, $text);
        $this->assertEquals('Hello, world!', $text->getText());
        $this->assertEquals('521234567890', $text->message->getFrom());
    }

    public function test_handler_button_returns_button_payload()
    {
        $handler = new WebhookHandler($this->buttonPayload()['entry'][0]['changes'][0]['value']);
        $button = $handler->button();

        $this->assertInstanceOf(ButtonPayload::class, $button);
        $this->assertEquals('Yes, confirm', $button->getText());
        $this->assertEquals('confirm-yes', $button->getPayload());
    }

    public function test_handler_interactive_returns_interactive_payload()
    {
        $handler = new WebhookHandler($this->interactivePayload()['entry'][0]['changes'][0]['value']);
        $interactive = $handler->interactive();

        $this->assertInstanceOf(InteractivePayload::class, $interactive);
        $this->assertEquals('button_reply', $interactive->getType());
        $this->assertEquals('btn-1', $interactive->getId());
        $this->assertEquals('First option', $interactive->getTitle());
    }

    public function test_handler_statuses_returns_array_of_status_payload()
    {
        $handler = new WebhookHandler($this->statusPayload()['entry'][0]['changes'][0]['value']);
        $statuses = $handler->statuses();

        $this->assertCount(1, $statuses);
        $this->assertInstanceOf(StatusPayload::class, $statuses[0]);
        $this->assertEquals('wamid.STATUS123', $statuses[0]->getId());
        $this->assertEquals('delivered', $statuses[0]->getStatus());
        $this->assertEquals('521234567890', $statuses[0]->getRecipientId());
        $this->assertTrue($statuses[0]->isDelivered());
    }

    public function test_handler_text_throws_exception_for_non_text_message()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Expected message type 'text'");

        $handler = new WebhookHandler($this->buttonPayload()['entry'][0]['changes'][0]['value']);
        $handler->text();
    }

    public function test_handler_get_type()
    {
        $handler = new WebhookHandler($this->textPayload()['entry'][0]['changes'][0]['value']);
        $this->assertEquals('text', $handler->getType());
    }

    public function test_status_payload_predicates()
    {
        $delivered = new StatusPayload(['id' => 'a', 'status' => 'delivered', 'recipient_id' => 'r']);
        $read = new StatusPayload(['id' => 'b', 'status' => 'read', 'recipient_id' => 'r']);
        $failed = new StatusPayload(['id' => 'c', 'status' => 'failed', 'recipient_id' => 'r']);

        $this->assertTrue($delivered->isDelivered());
        $this->assertFalse($delivered->isRead());

        $this->assertTrue($read->isRead());
        $this->assertFalse($read->isDelivered());

        $this->assertTrue($failed->isFailed());
    }

    public function test_status_payload_constants()
    {
        $this->assertEquals('sent', StatusPayload::STATUS_SENT);
        $this->assertEquals('delivered', StatusPayload::STATUS_DELIVERED);
        $this->assertEquals('read', StatusPayload::STATUS_READ);
        $this->assertEquals('failed', StatusPayload::STATUS_FAILED);
    }

    public function test_webhook_verify_returns_challenge_on_valid_token()
    {
        config(['laravel-whatsapp-api.verify_token' => 'my-secret-token']);

        $challenge = WhatsAppWebhook::verify('subscribe', 'my-secret-token', '1234567890');

        $this->assertEquals('1234567890', $challenge);
    }

    public function test_webhook_verify_throws_on_invalid_mode()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid mode");

        WhatsAppWebhook::verify('invalid', 'token', 'challenge');
    }

    public function test_webhook_verify_throws_on_invalid_token()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid verify token");

        config(['laravel-whatsapp-api.verify_token' => 'correct-token']);
        WhatsAppWebhook::verify('subscribe', 'wrong-token', 'challenge');
    }

    public function test_webhook_handle_returns_handler()
    {
        $payload = $this->textPayload()['entry'][0]['changes'][0]['value'];
        $handler = WhatsAppWebhook::handle($payload);

        $this->assertInstanceOf(WebhookHandler::class, $handler);
    }

    public function test_value_parses_message_context_when_present()
    {
        $payload = $this->textPayload();
        $payload['entry'][0]['changes'][0]['value']['messages'][0]['context'] = ['id' => 'wamid.ORIGINAL'];

        $handler = new WebhookHandler($payload['entry'][0]['changes'][0]['value']);
        $value = $handler->value();

        $this->assertNotNull($value->messages[0]->getContext());
        $this->assertEquals('wamid.ORIGINAL', $value->messages[0]->getContext()->getId());
    }
}
