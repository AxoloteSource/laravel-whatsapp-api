<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\AudioUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\DocumentUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\StickerUpload;
use Exception;

class WhatsAppMediaUploadsTest extends TestCase
{
    public function test_can_get_document_upload_instance()
    {
        $media = WhatsAppMedia::document('/path/to/file.pdf');
        $this->assertInstanceOf(DocumentUpload::class, $media);
    }

    public function test_can_get_audio_upload_instance()
    {
        $media = WhatsAppMedia::audio('/path/to/audio.mp3');
        $this->assertInstanceOf(AudioUpload::class, $media);
    }

    public function test_can_get_sticker_upload_instance()
    {
        $media = WhatsAppMedia::sticker('/path/to/sticker.webp');
        $this->assertInstanceOf(StickerUpload::class, $media);
    }

    public function test_document_upload_accepts_pdf_mime_type()
    {
        $upload = new DocumentUpload('/path/to/file.pdf', 'document');
        $upload->validFileType('application/pdf');
        $this->assertTrue(true);
    }

    public function test_document_upload_accepts_doc_mime_type()
    {
        $upload = new DocumentUpload('/path/to/file.doc', 'document');
        $upload->validFileType('application/msword');
        $this->assertTrue(true);
    }

    public function test_document_upload_accepts_docx_mime_type()
    {
        $upload = new DocumentUpload('/path/to/file.docx', 'document');
        $upload->validFileType('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $this->assertTrue(true);
    }

    public function test_document_upload_accepts_text_plain()
    {
        $upload = new DocumentUpload('/path/to/file.txt', 'document');
        $upload->validFileType('text/plain');
        $this->assertTrue(true);
    }

    public function test_document_upload_throws_exception_for_invalid_mime_type()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Mine type must be a valid document type");

        $upload = new DocumentUpload('/path/to/file.exe', 'document');
        $upload->validFileType('application/x-msdownload');
    }

    public function test_audio_upload_accepts_ogg_mime_type()
    {
        $upload = new AudioUpload('/path/to/audio.ogg', 'audio');
        $upload->validFileType('audio/ogg');
        $this->assertTrue(true);
    }

    public function test_audio_upload_accepts_mpeg_mime_type()
    {
        $upload = new AudioUpload('/path/to/audio.mp3', 'audio');
        $upload->validFileType('audio/mpeg');
        $this->assertTrue(true);
    }

    public function test_audio_upload_accepts_aac_mime_type()
    {
        $upload = new AudioUpload('/path/to/audio.aac', 'audio');
        $upload->validFileType('audio/aac');
        $this->assertTrue(true);
    }

    public function test_audio_upload_throws_exception_for_invalid_mime_type()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Mine type must be 'audio/aac', 'audio/mp4', 'audio/mpeg', 'audio/amr' or 'audio/ogg'");

        $upload = new AudioUpload('/path/to/audio.wav', 'audio');
        $upload->validFileType('audio/wav');
    }

    public function test_sticker_upload_accepts_webp_mime_type()
    {
        $upload = new StickerUpload('/path/to/sticker.webp', 'sticker');
        $upload->validFileType('image/webp');
        $this->assertTrue(true);
    }

    public function test_sticker_upload_throws_exception_for_invalid_mime_type()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Mine type must be 'image/webp'");

        $upload = new StickerUpload('/path/to/sticker.png', 'sticker');
        $upload->validFileType('image/png');
    }
}
