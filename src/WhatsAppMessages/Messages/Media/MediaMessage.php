<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class MediaMessage extends WhatsAppBase
{
    private Media $media;

    public function __construct(string $to, Media $media, $type = HeaderType::IMAGE)
    {
        parent::__construct($to, $type);
        $this->media = $media;
    }

    protected function action(): array
    {
        return [
            'type' => 'image',
            'image' => [
                "id" => $this->media->getId()
            ]
        ];
    }
}
