<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Media;

class MediaUrl extends WhatsAppBase
{
    private Media $media;

    public function __construct(string $to, string $url, string $type)
    {
        parent::__construct($to, $type);
        $this->media = new Media($type, $url);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->media->getLink();
    }

    protected function action(): array
    {
        return array_merge(
            ['type' => $this->media->getType()],
            $this->media->toArray()
        );
    }
}
