<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class Image extends WhatsAppBase
{
    private string $url;
    private string $caption;
    private string $type = "image";

    public function __construct($url, String $caption = '')
    {
        parent::__construct('', $this->type);
        $this->url = $url;
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    protected function action(): array
    {
        $payload = [
            'type' => $this->type,
            $this->type => [
                "link" => $this->url
            ]
        ];

        if ($this->caption !== '') {
            $payload[$this->type]['caption'] = $this->caption;
        }

        return $payload;
    }
}
