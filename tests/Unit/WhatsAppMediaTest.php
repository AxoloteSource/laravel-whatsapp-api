<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\ImageUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\VideoUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\RetrieveMedia;

class WhatsAppMediaTest extends TestCase
{
    public function test_can_get_image_upload_instance()
    {
        $media = WhatsAppMedia::image('/path/to/image.jpg');
        $this->assertInstanceOf(ImageUpload::class, $media);
    }

    public function test_can_get_video_upload_instance()
    {
        $media = WhatsAppMedia::video('/path/to/video.mp4');
        $this->assertInstanceOf(VideoUpload::class, $media);
    }

    public function test_can_get_retrieve_media_instance()
    {
        $media = WhatsAppMedia::retrieve('MEDIA_ID_123');
        $this->assertInstanceOf(RetrieveMedia::class, $media);
    }
}
