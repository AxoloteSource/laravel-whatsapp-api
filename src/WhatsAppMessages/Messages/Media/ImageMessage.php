<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Image;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class ImageMessage extends WhatsAppBase
{
    private Image $image;

    public function __construct(string $to, Image $image, $type = HeaderType::IMAGE)
    {
        parent::__construct($to, $type);
        $this->image = $image;
    }

    protected function action(): array
    {
        return [
            'type' => 'image',
            'image' => [
                "link" => $this->image->getUrl(),
            ]
        ];
    }
}
