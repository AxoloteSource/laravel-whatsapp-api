<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Exception;

class VideoUpload extends UploadMediaBase
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
        if ($mineType != 'video/mp4' && $mineType != 'video/3gp') {
            throw new Exception("Mine type must be 'video/mp4' or 'video/3gp' and it is $mineType");
        }
    }
}
