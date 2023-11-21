<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MediaType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\MediaUrl;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\InteractiveButtons;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\InteractiveList;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\MediaMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Raw;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Template;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Test;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Text\Text;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Image;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media\ImageMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Text\TextMessage;

/**
 * Documentation messages
 * https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages
 */
class WhatsAppMessages
{

    public static function interactiveButtons(string $to): InteractiveButtons
    {
        return new InteractiveButtons($to);
    }

    public static function interactiveList(string $to): InteractiveList
    {
        return new InteractiveList($to);
    }

    public static function text(string $to, $previewUrl = true): Text
    {
        return new Text($to, $previewUrl);
    }

    public static function textMessage(string $to, $previewUrl = true): TextMessage
    {
        return new TextMessage($to, $previewUrl);
    }

    public static function image(string $to, Media $media): MediaMessage
    {
        return new MediaMessage($to, $media, HeaderType::IMAGE);
    }

    public static function imageByUrl(string $to, string $url) : MediaUrl
    {
        return new MediaUrl($to, $url, MediaType::IMAGE);
    }

    public static function videoByUrl(string $to, string $url) : MediaUrl
    {
        return new MediaUrl($to, $url, MediaType::VIDEO);
    }

    public static function test(string $to)
    {
        return new Test($to);
    }

    public static function templete(string $to, string $templateName = null): Template
    {
        if ($templateName === null) {
            $templateName = config('laravel-whatsapp-api.default_initial_templete');
        }

        return new Template($to, $templateName);
    }

    /**
     * @throws \Exception
     */
    public static function raw(array $request, string $to = null, array $params = []): Raw
    {
        return new Raw($request, $to, $params);
    }

}
