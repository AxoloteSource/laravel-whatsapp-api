<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Exception;

class AudioUpload extends UploadMediaBase
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
        $validTypes = [
            'audio/aac',
            'audio/mp4',
            'audio/mpeg',
            'audio/amr',
            'audio/ogg',
        ];

        if (!in_array($mineType, $validTypes)) {
            throw new Exception("Mine type must be 'audio/aac', 'audio/mp4', 'audio/mpeg', 'audio/amr' or 'audio/ogg' and it is $mineType");
        }
    }
}
