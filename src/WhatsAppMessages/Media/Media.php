<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;

class Media extends WhatsAppBase
{
    private string $url;
    private string $mineType;
    private string $sha256;
    private $fileSize;
    private $id;
    private string $type;
    public function __construct($url, $mineType, $sha256, $fileSize, $id, $type)
    {
        parent::__construct('', $type);
        $this->url = $url;
        $this->mineType = $mineType;
        $this->sha256 = $sha256;
        $this->fileSize = $fileSize;
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMineType(): string
    {
        return $this->mineType;
    }

    /**
     * @return string
     */
    public function getSha256(): string
    {
        return $this->sha256;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    protected function action(): array
    {
        return [
            'type' => $this->type,
            $this->type => [
                "id" => $this->id
            ]
        ];
    }
}
