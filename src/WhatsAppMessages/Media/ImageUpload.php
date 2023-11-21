<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Exception;

class ImageUpload extends UploadMediaBase
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
        if ($mineType != 'image/jpeg' && $mineType != 'image/png') {
            throw new Exception("Mine type must be 'image/jpeg' or 'image/png' and it is $mineType");
        }
    }
}
