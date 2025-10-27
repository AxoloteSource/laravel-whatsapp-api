<?php
namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\ComponentsType;
use Exception;

class ButtonComponent implements ComponentsInterface
{
    public const string SUB_TYPE_QUICK_REPLY = 'QUICK_REPLY';
    public const string SUB_TYPE_URL = 'URL';

    private string $subType;
    private int $index;
    private array $component;

    /**
     * @param array $components
     */
    private function __construct(array $components, string $subType = self::SUB_TYPE_QUICK_REPLY)
    {
        $this->component = $components;
        $this->subType = $subType;
    }

    /**
     * @param array $components
     * @return ButtonComponent
     */
    static function create(array $components, string $subType = self::SUB_TYPE_QUICK_REPLY): ButtonComponent
    {
        return new ButtonComponent($components, $subType);
    }

    /**
     * @return int
     */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ComponentsType::BUTTON;
    }

    /**
     * @return array
     */
    public function getComponent(): array
    {
        if (!isset($this->index)) {
            throw new Exception('$index cannot be null');
        }

        return [
            'type' => $this->getType(),
            'sub_type' => $this->subType,
            'index' => $this->index,
            'parameters' => $this->component
        ];
    }
}
