<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects;

class Media
{
    private string $type;

    /**
     * Required when type is audio, document, image, sticker, or video and you are not using a link.
     * The media object ID. Do not use this field when message type is set to text.
     */
    private ?int $id = null;

    /**
     * Required when type is audio, document, image, sticker, or video and you are not using an uploaded media ID.
     * The protocol and URL of the media to be sent. Use only with HTTP/HTTPS URLs.
     * Do not use this field when message type is set to text.
     */
    private ?string $link = null;

    /**
     * Optional.
     * Describes the filename for the specific document. Use only with document media.
     * The extension of the filename will specify what format the document is displayed as in WhatsApp.
     */
    private ?string $filename = null;

    /**
     * Optional. Only used for On-Premises API.
     * This path is optionally used with a link when the HTTP/HTTPS link is not directly accessible and requires additional configurations like a bearer token.
     * For information on configuring providers, see the Media Providers documentation.
     * https://developers.facebook.com/docs/whatsapp/api/settings/media-providers
     */
    private $provider;

    public function __construct(string $type, $linkOrId, $filename = null, $provider = null)
    {
        $this->type = $type;

        filter_var($linkOrId, FILTER_VALIDATE_URL)
            ? $this->link = $linkOrId
            : $this->id = $linkOrId;

        $this->filename = $filename;
        $this->provider = $provider;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        if ($this->id != null) {
            return [
                $this->type => [
                    'id' => $this->id,
                ]
            ];
        }

        $payload = [
            $this->type => [
                'link' => $this->link,
            ]
        ];

        if ($this->provider != null) {
            $payload[$this->type]['provider'] = $this->provider;
        }

        if ($this->filename != null) {
            $payload[$this->type]['filename'] = $this->filename;
        }

        return $payload;
    }
}
