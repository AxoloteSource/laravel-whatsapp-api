<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Exception;

class StickerUpload extends UploadMediaBase
{

    public function __construct(string $path, string $type)
    {
        parent::__construct($path, $type);
    }

    /**
     * @throws Exception
     */
    public function validFileType(string $mineType): void
    {
        if ($mineType != 'image/webp') {
            throw new Exception("Mine type must be 'image/webp' and it is $mineType");
        }
    }
}
