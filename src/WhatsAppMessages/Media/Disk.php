<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Illuminate\Filesystem\FilesystemAdapter;
use Storage;

trait Disk
{
    /**
     * Throw exception if is invalid
     */
    public function disk($disk = 'public') : FilesystemAdapter
    {
        return Storage::disk($disk);
    }
}
