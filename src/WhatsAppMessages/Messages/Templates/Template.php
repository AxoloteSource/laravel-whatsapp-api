<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\ComponentsType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components\BodyComponent;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components\ButtonComponent;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components\HeaderComponent;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Exception;

/**
 * Show documentation here
 * https://developers.facebook.com/docs/whatsapp/on-premises/reference/messages#template-object
 */
class Template extends WhatsAppBase
{
    private string $templeteName;
    private string $languageCode;
    private array $components = [];

    public function __construct(string $to, string $templeteName)
    {
        $this->templeteName = $templeteName;
        parent::__construct($to, MessageType::TEMPLATE);
    }

    /**
     * Set template language, You can show languages code here
     * https://developers.facebook.com/docs/whatsapp/api/messages/message-templates#language
     *
     * @param string $languageCode
     * @return $this
     */
    public function language(string $languageCode): Template
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * Add components to template ()
     *
     * @throws Exception
     */
    public function addComponents(array $components): Template
    {
        $buttonIndex = 0;

        /**
         * @var $component BodyComponent|ButtonComponent|HeaderComponent
         */
        foreach ($components as $component) {
            if ($component->getType() == ComponentsType::BUTTON) {
                $component->setIndex($buttonIndex);
                $buttonIndex += 1;
            }

            array_push($this->components, $component->getComponent());
        }
        return $this;
    }

    /**
     *
     *
     * @return array
     * @throws Exception
     */
    protected function action(): array
    {
        if (!isset($this->languageCode)) {
            throw new Exception('languageCode must not null. Use method language(string $languageCode) to set language code');
        }

        return [
            'type' => MessageType::TEMPLATE,
            MessageType::TEMPLATE => [
                'name' => $this->templeteName,
                'language' => [
                    'code' => $this->languageCode,
                ],
                'components' => $this->components
            ]
        ];
    }
}
