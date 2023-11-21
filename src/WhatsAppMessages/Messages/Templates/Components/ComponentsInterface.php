<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Templates\Components;

interface ComponentsInterface
{
    public function getComponent() : array;
    public function getType() : string;
    /**
     * @return BodyComponent|ButtonComponent|HeaderComponent
     */
    public static function create(array $components);

}
