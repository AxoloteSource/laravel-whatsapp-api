<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\HeaderType;
use Http;

class RetrieveMedia
{
    use Config, Disk;

    private string $mediaId;

    public function __construct(string $mediaId)
    {
        $this->initialize();
        $this->mediaId = $mediaId;
    }

    public function get(): Media
    {
        $response = Http::withHeaders($this->headers())
            ->get("$this->baseUrl/$this->mediaId");

        $media = $response->object();

        return new Media(
            $media->url,
            $media->mime_type,
            $media->sha256,
            $media->file_size,
            $media->id,
            $this->mimeTypeToType($media->mime_type)
        );
    }

    private function headers(): array
    {
        return [
            'Authorization' => "Bearer $this->bearer",
        ];
    }

    private function mimeTypeToType(string $mineType) : string
    {
        if ($mineType == 'image/webp') {
            return 'sticker';
        }

        $type = explode('/', $mineType)[0];

        if ($type == HeaderType::AUDIO || $type == HeaderType::IMAGE || $type == HeaderType::VIDEO) {
            return $type;
        }

        return HeaderType::DOCUMENT;
    }
}
