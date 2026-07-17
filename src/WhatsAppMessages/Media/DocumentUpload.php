<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Exception;

class DocumentUpload extends UploadMediaBase
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
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ];

        if (!in_array($mineType, $validTypes)) {
            throw new Exception("Mine type must be a valid document type (pdf, doc, docx, xls, xlsx, ppt, pptx, txt) and it is $mineType");
        }
    }
}
