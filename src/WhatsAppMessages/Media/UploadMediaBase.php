<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Media;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;
use Exception;
use Http;
use Illuminate\Http\Client\Response;
use Storage;

abstract class UploadMediaBase
{
    use Config, Disk;

    private string $path;
    private string $type;

    abstract public function validFileType(string $mineType) : void;

    public function __construct(string $path, string $type)
    {
        $this->initialize();
        $this->path = $path;
        $this->type = $type;
    }

    /**
     * @throws Exception
     */
    public function upload(): Media
    {
        $storage = $this->disk();
        $this->validFileType($storage->mimeType($this->path));

        $response =  Http::withHeaders($this->headers())
            ->attach('type', $this->type)
            ->attach('file', $this->path, $this->path)
            ->attach('messaging_product', 'whatsapp')
            ->post("$this->baseUrl$this->phoneNumberID/media");

        //TODO VALIDAR LA RESPUESTA
        if ($response->status() != 200) {
            throw new Exception($response->body());
        }

        return WhatsAppMedia::retrieve($response->object()->id)->get();
    }

    private function headers() : array
    {
        return [
            'Authorization' => "Bearer $this->bearer",
        ];
    }

}
