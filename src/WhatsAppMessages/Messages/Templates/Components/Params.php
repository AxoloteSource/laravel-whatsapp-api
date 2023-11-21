<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components;

class Params
{
    public static function text(string $text) : array
    {
        return [
            'type' => 'text',
            'text' => $text
        ];
    }

    public static function button(string $payload): array
    {
        return [
            "type" => "payload",
            "payload" => $payload
        ];
    }

    public static function imageFromUrl(string $url) : array
    {
        return [
            'type' => 'image',
            'image' => [
                "link" => $url
            ]
        ];
    }


    /*public static function document(string $url) : array
    {
        return [
            'type' => 'document',
            //TODO
        ];
    }

    public static function video(string $url) : array
    {
        return [
            'type' => 'video',
            //TODO
        ];
    }*/

}
