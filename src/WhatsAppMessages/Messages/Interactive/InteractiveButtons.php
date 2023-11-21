<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Footer;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Header;

class InteractiveButtons extends WhatsAppBase
{
    use Header, Body, Footer;

    private array $buttons = [];

    //TODO ADD VALIDATION TITE MAX 20 CHARACTERS
    public function addButton(string $title, string $id): InteractiveButtons
    {
        if (count($this->buttons) >= 3) {
            logger('Error mas de 3 botones');
        }

        $this->buttons[] = [
            'type' => 'reply',
            'reply' => [
                'id' => $id,
                'title' => $title,
            ],
        ];

        return $this;
    }

    protected function action(): array
    {
        $interactive = [
            'type' => 'button',
            'body' => [
                'text' => $this->getBody(),
            ],
            'action' => [
                'buttons' => $this->buttons
            ],
        ];

        if ($this->hasHeader()) {
            $interactive = array_merge($interactive,
                $this->getHeader()
            );
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
            'interactive' => $interactive
        ];
    }
}
