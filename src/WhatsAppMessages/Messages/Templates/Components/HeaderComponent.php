<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\ComponentsType;

class HeaderComponent implements ComponentsInterface
{
    /**
     * @var array
     */
    private array $component;

    /**
     *
     * @param array $components
     */
    private function __construct(array $components)
    {
        $this->component = $components;
    }

    public static function create(array $components): HeaderComponent
    {
        return new HeaderComponent($components);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ComponentsType::HEADER;
    }

    /**
     * @return array
     */
    public function getComponent(): array
    {
        return [
            'type' => $this->getType(),
            'parameters' => $this->component
        ];
    }
}
