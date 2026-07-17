<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\MediaUrl;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\AudioMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\DocumentMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\StickerMessage;

class WhatsAppMediaMessagesTest extends TestCase
{
    public function test_can_create_document_message_by_id()
    {
        $media = new Media('http://example.com/file.pdf', 'application/pdf', 'sha256', 2048, 'media_id_doc', 'document');
        $doc = WhatsAppMessages::document('521234567890', $media);
        $this->assertInstanceOf(DocumentMessage::class, $doc);
    }

    public function test_can_create_document_message_by_url()
    {
        $doc = WhatsAppMessages::documentByUrl('521234567890', 'https://example.com/invoice.pdf');
        $this->assertInstanceOf(MediaUrl::class, $doc);
    }

    public function test_can_create_document_message_by_url_with_filename()
    {
        $doc = WhatsAppMessages::documentByUrl('521234567890', 'https://example.com/file', 'invoice.pdf');
        $this->assertInstanceOf(MediaUrl::class, $doc);
    }

    public function test_document_message_to_array_with_id()
    {
        $media = new Media('http://example.com/file.pdf', 'application/pdf', 'sha256', 2048, 'media_id_doc', 'document');
        $doc = WhatsAppMessages::document('521234567890', $media);

        $payload = $doc->toArray();

        $this->assertEquals('document', $payload['type']);
        $this->assertEquals('media_id_doc', $payload['document']['id']);
    }

    public function test_document_message_to_array_with_caption_and_filename()
    {
        $media = new Media('http://example.com/file.pdf', 'application/pdf', 'sha256', 2048, 'media_id_doc', 'document');
        $doc = WhatsAppMessages::document('521234567890', $media)
            ->caption('My invoice')
            ->filename('invoice-2024.pdf');

        $payload = $doc->toArray();

        $this->assertEquals('document', $payload['type']);
        $this->assertEquals('media_id_doc', $payload['document']['id']);
        $this->assertEquals('My invoice', $payload['document']['caption']);
        $this->assertEquals('invoice-2024.pdf', $payload['document']['filename']);
    }

    public function test_document_by_url_to_array_contains_link()
    {
        $doc = WhatsAppMessages::documentByUrl('521234567890', 'https://example.com/invoice.pdf', 'invoice.pdf');
        $payload = $doc->toArray();

        $this->assertEquals('document', $payload['type']);
        $this->assertEquals('https://example.com/invoice.pdf', $payload['document']['link']);
        $this->assertEquals('invoice.pdf', $payload['document']['filename']);
    }

    public function test_can_create_audio_message_by_id()
    {
        $media = new Media('http://example.com/audio.mp3', 'audio/mpeg', 'sha256', 512, 'media_id_audio', 'audio');
        $audio = WhatsAppMessages::audio('521234567890', $media);
        $this->assertInstanceOf(AudioMessage::class, $audio);
    }

    public function test_can_create_audio_message_by_url()
    {
        $audio = WhatsAppMessages::audioByUrl('521234567890', 'https://example.com/audio.ogg');
        $this->assertInstanceOf(MediaUrl::class, $audio);
    }

    public function test_audio_message_to_array_with_id()
    {
        $media = new Media('http://example.com/audio.mp3', 'audio/mpeg', 'sha256', 512, 'media_id_audio', 'audio');
        $audio = WhatsAppMessages::audio('521234567890', $media);

        $payload = $audio->toArray();

        $this->assertEquals('audio', $payload['type']);
        $this->assertEquals('media_id_audio', $payload['audio']['id']);
    }

    public function test_audio_message_to_array_with_link()
    {
        $audio = WhatsAppMessages::audioByUrl('521234567890', 'https://example.com/audio.ogg');
        $payload = $audio->toArray();

        $this->assertEquals('audio', $payload['type']);
        $this->assertEquals('https://example.com/audio.ogg', $payload['audio']['link']);
    }

    public function test_can_create_sticker_message_by_id()
    {
        $media = new Media('http://example.com/sticker.webp', 'image/webp', 'sha256', 32, 'media_id_sticker', 'sticker');
        $sticker = WhatsAppMessages::sticker('521234567890', $media);
        $this->assertInstanceOf(StickerMessage::class, $sticker);
    }

    public function test_can_create_sticker_message_by_url()
    {
        $sticker = WhatsAppMessages::stickerByUrl('521234567890', 'https://example.com/sticker.webp');
        $this->assertInstanceOf(MediaUrl::class, $sticker);
    }

    public function test_sticker_message_to_array_with_id()
    {
        $media = new Media('http://example.com/sticker.webp', 'image/webp', 'sha256', 32, 'media_id_sticker', 'sticker');
        $sticker = WhatsAppMessages::sticker('521234567890', $media);

        $payload = $sticker->toArray();

        $this->assertEquals('sticker', $payload['type']);
        $this->assertEquals('media_id_sticker', $payload['sticker']['id']);
    }

    public function test_sticker_message_to_array_with_link()
    {
        $sticker = WhatsAppMessages::stickerByUrl('521234567890', 'https://example.com/sticker.webp');
        $payload = $sticker->toArray();

        $this->assertEquals('sticker', $payload['type']);
        $this->assertEquals('https://example.com/sticker.webp', $payload['sticker']['link']);
    }

    public function test_document_message_includes_recipient_in_payload()
    {
        $media = new Media('http://example.com/file.pdf', 'application/pdf', 'sha256', 2048, 'media_id_doc', 'document');
        $doc = WhatsAppMessages::document('521234567890', $media);

        $payload = $doc->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_audio_message_includes_recipient_in_payload()
    {
        $media = new Media('http://example.com/audio.mp3', 'audio/mpeg', 'sha256', 512, 'media_id_audio', 'audio');
        $audio = WhatsAppMessages::audio('521234567890', $media);

        $payload = $audio->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }
}
