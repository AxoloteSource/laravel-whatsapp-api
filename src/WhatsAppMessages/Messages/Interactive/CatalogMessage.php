<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\InteractiveType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Footer;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Header;
use Exception;

class CatalogMessage extends WhatsAppBase
{
    use Header, Body, Footer;

    private ?string $thumbnailProductRetailerId = null;

    public function __construct(string $to)
    {
        parent::__construct($to, MessageType::INTERACTIVE);
    }

    public function thumbnailProductRetailerId(string $id): CatalogMessage
    {
        $this->thumbnailProductRetailerId = $id;

        return $this;
    }

    protected function action(): array
    {
        $interactive = [
            'type' => InteractiveType::CATALOG_MESSAGE,
            'body' => [
                'text' => $this->getBody(),
            ],
            'action' => [
                'name' => InteractiveType::CATALOG_MESSAGE,
                'parameters' => [],
            ],
        ];

        if ($this->thumbnailProductRetailerId !== null) {
            $interactive['action']['parameters']['thumbnail_product_retailer_id'] = $this->thumbnailProductRetailerId;
        }

        if ($this->hasHeader()) {
            $interactive = array_merge($interactive, $this->getHeader());
        }

        if ($this->hasFooter()) {
            $interactive = array_merge($interactive, [
                'footer' => [
                    'text' => $this->getFooter(),
                ]
            ]);
        }

        return [
            'type' => 'interactive',
            'interactive' => $interactive,
        ];
    }
}
