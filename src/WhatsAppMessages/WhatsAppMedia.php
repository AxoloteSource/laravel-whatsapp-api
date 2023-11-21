<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\ImageUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\VideoUpload;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\RetrieveMedia;

class WhatsAppMedia
{
    public static function image(string $path) : ImageUpload
    {
        return new ImageUpload($path, HeaderType::IMAGE);
    }

    public static function document()
    {
        //TODO
    }

    public static function audio()
    {
        //TODO
    }

    public static function video(string $path)
    {
        return new VideoUpload($path, HeaderType::VIDEO);
    }

    public static function sticker()
    {
        //TODO
    }

    public static function retrieve(string $id): RetrieveMedia
    {
        return new RetrieveMedia($id);
    }
}
