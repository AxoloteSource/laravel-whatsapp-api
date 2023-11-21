<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Payload\NestedClasses\Payload;

class InteractivePayload extends Payload
{
    private string $type;
    private string $id;
    private string $title;
    private string $description;

    const TYPE_BUTTON_REPLY = 'button_reply';
    const TYPE_LIST_REPLY = 'list_reply';

    public function __construct(array $payload, int $messageIndex)
    {
        logger_env('---------- COSTRUCTOR InteractivePayload -----------', 'whatsapp');
        logger_env($payload);

        $interactive = $payload['messages'][$messageIndex]['interactive'];
        parent::__construct($payload, $messageIndex);

        //TODO VALIDAR SI SE DEBE DE TOMAR EL MENSAJE 0
        $this->type = $interactive['type'];

        $this->id = $interactive[$this->type]['id'];
        $this->title = $interactive[$this->type]['title'];

        $this->description = $this->type == self::TYPE_LIST_REPLY && isset($interactive[$this->type]['description'])
            ? $interactive[$this->type]['description']
            : '';
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed|string
     */
    public function getDescription()
    {
        return $this->description;
    }

}
