<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Footer;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Header;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Row;
use Exception;

class InteractiveList extends WhatsAppBase
{
    use Header, Body, Footer;

    private array $sections = [];
    private string $button;

    public function button(string $button): InteractiveList
    {
        $this->button = $button;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function addSection(string $title, array $rows): InteractiveList
    {
        if (strlen($title) > 20) {
            throw new Exception("Row title is too long. Max length is 20");
        }

        $this->sections[] = [
            'title' => $title,
            'rows' => array_map(function (Row $row) {
                return $row->getRow();
            }, $rows),
        ];

        return $this;
    }


    protected function action(): array
    {
        $interactive = [
            'type' => 'list',
            'body' => [
                'text' => $this->getBody(),
            ],
            'action' => [
                'button' => $this->button,
                'sections' => $this->sections
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
