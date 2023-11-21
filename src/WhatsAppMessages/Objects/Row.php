<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects;

use Exception;

class Row
{
    private string $id;
    private string $title;
    private string $description;

    /**
     * @throws Exception
     */
    public function __construct(string $title, string $id, string $description = '')
    {
        if (strlen($title) > 24) {
            throw new Exception("Row title is too long. Max length is 24");
        }
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getRow() : array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description
        ];
    }

    /**
     * @throws Exception
     */
    public static function create(string $title, string $id, string $description = '') : Row
    {
        return new Row($title, $id, $description);
    }
}
