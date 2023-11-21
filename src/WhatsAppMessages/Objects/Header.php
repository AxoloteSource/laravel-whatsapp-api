<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MediaType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media\MediaUrl;
use Exception;

trait Header
{

    /**
     * Required.
     *
     * @var string|array
     */
    private $header;

    /**
     * Required.
     *
     * The header type you would like to use. Supported values:
     *
     * text: Used for List Messages, Reply Buttons, and Multi-Product Messages. (Maximum length: 60 characters.)
     * video: Used for Reply Buttons.
     * image: Used for Reply Buttons.
     * document: Used for Reply Buttons.
     */
    private string $headerType;

    /**
     * Text for the header. Formatting allows emojis, but not markdown.
     * Maximum length: 60 characters.
     *
     * @param string $header
     * @throws Exception
     */
    public function setHeaderText(string $header)
    {
        if (strlen($header) > 60) {
            throw new Exception("Value must be <= 60");
        }

        $this->headerType = HeaderType::TEXT;
        $this->header = $header;

        return $this;
    }

    public function setHeaderImage($urlOrId, $provider = null)
    {
        return $this->setHeaderMedia(MediaType::IMAGE, $urlOrId, $provider);
    }

    public function setHeaderVideo($urlOrId, $provider = null)
    {
        return $this->setHeaderMedia(MediaType::VIDEO, $urlOrId, $provider);
    }

    public function setHeaderDocument($urlOrId, $provider = null, $fileName = null)
    {
        return $this->setHeaderMedia(MediaType::DOCUMENT, $urlOrId, $provider, $fileName);
    }

    protected function getHeader(): array
    {
        if ($this->headerType == HeaderType::TEXT ) {
            return [
                'header' => [
                    'type' => $this->headerType,
                    $this->headerType => $this->header
                ]
            ];
        }

        return [
            'header' => array_merge(
                ['type' => $this->headerType],
                $this->header
            )
        ];
    }

    protected function getHeaderType(): string
    {
        return $this->headerType;
    }

    protected function hasHeader(): bool
    {
        return $this->header != null;
    }

    private function setHeaderMedia(string $type, $urlOrId, $provider = null, string $fileName = null)
    {
        $media = new Media($type, $urlOrId, $fileName, $provider);

        $this->headerType = $type;
        $this->header = $media->toArray();

        return $this;
    }

}
