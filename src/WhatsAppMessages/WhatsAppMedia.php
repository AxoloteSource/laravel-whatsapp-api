<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\AudioUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\DocumentUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\ImageUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\RetrieveMedia;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\StickerUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\VideoUpload;

class WhatsAppMedia
{
    public static function image(string $path) : ImageUpload
    {
        return new ImageUpload($path, HeaderType::IMAGE);
    }

    public static function document(string $path) : DocumentUpload
    {
        return new DocumentUpload($path, HeaderType::DOCUMENT);
    }

    public static function audio(string $path) : AudioUpload
    {
        return new AudioUpload($path, HeaderType::AUDIO);
    }

    public static function video(string $path)
    {
        return new VideoUpload($path, HeaderType::VIDEO);
    }

    public static function sticker(string $path) : StickerUpload
    {
        return new StickerUpload($path, HeaderType::STICKER);
    }

    public static function retrieve(string $id): RetrieveMedia
    {
        return new RetrieveMedia($id);
    }
}
