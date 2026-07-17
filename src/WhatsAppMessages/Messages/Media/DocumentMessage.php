<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\Media;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class DocumentMessage extends WhatsAppBase
{
    private Media $media;
    private ?string $caption = null;
    private ?string $filename = null;

    public function __construct(string $to, Media $media, $type = HeaderType::DOCUMENT)
    {
        parent::__construct($to, $type);
        $this->media = $media;
    }

    public function caption(string $caption): DocumentMessage
    {
        $this->caption = $caption;

        return $this;
    }

    public function filename(string $filename): DocumentMessage
    {
        $this->filename = $filename;

        return $this;
    }

    protected function action(): array
    {
        $document = [];

        if ($this->media->getId() !== null) {
            $document['id'] = $this->media->getId();
        } else {
            $document['link'] = $this->media->getLink();
        }

        if ($this->caption !== null) {
            $document['caption'] = $this->caption;
        }

        if ($this->filename !== null) {
            $document['filename'] = $this->filename;
        }

        return [
            'type' => 'document',
            'document' => $document,
        ];
    }
}
