<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class StickerMessage extends WhatsAppBase
{
    private Media $media;

    public function __construct(string $to, Media $media, $type = 'sticker')
    {
        parent::__construct($to, $type);
        $this->media = $media;
    }

    protected function action(): array
    {
        $sticker = [];

        if ($this->media->getId() !== null) {
            $sticker['id'] = $this->media->getId();
        } else {
            $sticker['link'] = $this->media->getLink();
        }

        return [
            'type' => 'sticker',
            'sticker' => $sticker,
        ];
    }
}
