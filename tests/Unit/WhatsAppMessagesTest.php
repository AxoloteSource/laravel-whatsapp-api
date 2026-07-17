<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\InteractiveButtons;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\InteractiveList;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Text\Text;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Text\TextMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\MediaMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\MediaUrl;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Template;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Test;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Raw;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;

class WhatsAppMessagesTest extends TestCase
{
    public function test_can_create_interactive_buttons()
    {
        $buttons = WhatsAppMessages::interactiveButtons('521234567890');
        $this->assertInstanceOf(InteractiveButtons::class, $buttons);
    }

    public function test_can_create_interactive_list()
    {
        $list = WhatsAppMessages::interactiveList('521234567890');
        $this->assertInstanceOf(InteractiveList::class, $list);
    }

    public function test_can_create_text_message()
    {
        $text = WhatsAppMessages::text('521234567890');
        $this->assertInstanceOf(Text::class, $text);
    }

    public function test_can_create_text_message_with_preview_url_disabled()
    {
        $text = WhatsAppMessages::text('521234567890', false);
        $this->assertInstanceOf(Text::class, $text);
    }

    public function test_can_create_text_message_class()
    {
        $textMessage = WhatsAppMessages::textMessage('521234567890');
        $this->assertInstanceOf(TextMessage::class, $textMessage);
    }

    public function test_can_create_text_message_class_with_preview_url_disabled()
    {
        $textMessage = WhatsAppMessages::textMessage('521234567890', false);
        $this->assertInstanceOf(TextMessage::class, $textMessage);
    }

    public function test_can_create_image_message()
    {
        $media = new Media('http://example.com/image.jpg', 'image/jpeg', 'sha256hash', 1024, 'media_id_123', 'image');
        $image = WhatsAppMessages::image('521234567890', $media);
        $this->assertInstanceOf(MediaMessage::class, $image);
    }

    public function test_can_create_image_by_url()
    {
        $image = WhatsAppMessages::imageByUrl('521234567890', 'http://example.com/image.jpg');
        $this->assertInstanceOf(MediaUrl::class, $image);
    }

    public function test_can_create_video_by_url()
    {
        $video = WhatsAppMessages::videoByUrl('521234567890', 'http://example.com/video.mp4');
        $this->assertInstanceOf(MediaUrl::class, $video);
    }

    public function test_can_create_test_template()
    {
        $test = WhatsAppMessages::test('521234567890');
        $this->assertInstanceOf(Test::class, $test);
    }

    public function test_can_create_template()
    {
        $template = WhatsAppMessages::templete('521234567890', 'my_template');
        $this->assertInstanceOf(Template::class, $template);
    }

    public function test_can_create_template_with_default_name()
    {
        config(['laravel-whatsapp-api.default_initial_templete' => 'default']);
        $template = WhatsAppMessages::templete('521234567890');
        $this->assertInstanceOf(Template::class, $template);
    }

    public function test_can_create_raw_message()
    {
        $raw = WhatsAppMessages::raw([
            'type' => 'text',
            'text' => ['body' => 'Hello']
        ], '521234567890');
        $this->assertInstanceOf(Raw::class, $raw);
    }

    public function test_can_create_raw_message_with_params()
    {
        $raw = WhatsAppMessages::raw([
            'type' => 'text',
            'text' => ['body' => 'Hello {{name}}']
        ], '521234567890', ['name' => 'World']);
        $this->assertInstanceOf(Raw::class, $raw);
    }

    public function test_can_enable_fake_mode()
    {
        WhatsAppMessages::fake();
        $this->assertTrue(WhatsAppMessages::isFake());
    }

    public function test_is_fake_returns_false_by_default()
    {
        // Reset the static property before testing
        $reflection = new \ReflectionClass(WhatsAppMessages::class);
        $property = $reflection->getProperty('isFake');
        $property->setAccessible(true);
        $property->setValue(null, false);
        
        $this->assertFalse(WhatsAppMessages::isFake());
    }
}
