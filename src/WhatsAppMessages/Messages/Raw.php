<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages;

use Exception;

class Raw extends WhatsAppBase
{

    private array $request;
    private array $params;

    /**
     * @throws Exception
     */
    public function __construct(array $request, string $to = null, array $params = [])
    {
        if ($to === null) {
            $to = $request['to'];
        }

        if ($to === null) {
            throw new Exception('No to number provided');
        }

        parent::__construct($to, $request['type']);
        $this->request = $request;
        $this->params = $params;
    }

    protected function action(): array
    {
        $date = json_encode($this->request[$this->request['type']]);
        foreach ($this->params as $key => $param) {
            $date = str_replace(
                "{{$key}}",
                $param,
                $date
            );
        }

        $this->request[$this->request['type']] = json_decode($date, true);

        return [
            'type' => $this->request['type'],
            $this->request['type'] => $this->request[$this->request['type']]
        ];
    }
}
