<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class AudioMessage extends WhatsAppBase
{
    private Media $media;

    public function __construct(string $to, Media $media, $type = 'audio')
    {
        parent::__construct($to, $type);
        $this->media = $media;
    }

    protected function action(): array
    {
        $audio = [];

        if ($this->media->getId() !== null) {
            $audio['id'] = $this->media->getId();
        } else {
            $audio['link'] = $this->media->getLink();
        }

        return [
            'type' => 'audio',
            'audio' => $audio,
        ];
    }
}
