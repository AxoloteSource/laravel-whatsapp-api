<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\InteractiveType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Footer;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Header;
use Exception;

class ProductListMessage extends WhatsAppBase
{
    use Header, Body, Footer;

    private string $catalogId;
    private array $sections = [];

    public function __construct(string $to, string $catalogId)
    {
        parent::__construct($to, MessageType::INTERACTIVE);
        $this->catalogId = $catalogId;
    }

    public function addSection(ProductSection $section): ProductListMessage
    {
        $this->sections[] = $section->toArray();

        return $this;
    }

    protected function action(): array
    {
        if (empty($this->sections)) {
            throw new Exception("At least one product section is required for product list messages");
        }

        $interactive = [
            'type' => InteractiveType::PRODUCT_LIST,
            'body' => [
                'text' => $this->getBody(),
            ],
            'action' => [
                'name' => InteractiveType::PRODUCT_LIST,
                'catalog_id' => $this->catalogId,
                'sections' => $this->sections,
            ],
        ];

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
