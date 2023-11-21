<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\ComponentsType;

class BodyComponent implements ComponentsInterface
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

    /**
     * @param array $components
     * @return BodyComponent
     */
    static function create(array $components) : BodyComponent
    {
        return new BodyComponent($components);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ComponentsType::BODY;
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
