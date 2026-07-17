<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\InteractiveType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Constants\MessageType;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\WhatsAppBase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Body;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Footer;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Objects\Header;
use Exception;

class FlowMessage extends WhatsAppBase
{
    use Header, Body, Footer;

    public const FLOW_ACTION_NAVIGATE = 'navigate';
    public const FLOW_ACTION_DATA_EXCHANGE = 'data_exchange';

    private string $flowId;
    private string $flowMessageVersion = '3';
    private ?string $flowToken = null;
    private string $flowCta = 'Start';
    private string $flowAction = self::FLOW_ACTION_NAVIGATE;
    private ?array $flowActionPayload = null;

    public function __construct(string $to, string $flowId)
    {
        parent::__construct($to, MessageType::INTERACTIVE);
        $this->flowId = $flowId;
    }

    public function flowMessageVersion(string $version): FlowMessage
    {
        $this->flowMessageVersion = $version;

        return $this;
    }

    public function flowToken(string $token): FlowMessage
    {
        $this->flowToken = $token;

        return $this;
    }

    public function flowCta(string $cta): FlowMessage
    {
        $this->flowCta = $cta;

        return $this;
    }

    public function flowAction(string $action): FlowMessage
    {
        $this->flowAction = $action;

        return $this;
    }

    public function flowActionPayload(array $payload): FlowMessage
    {
        $this->flowActionPayload = $payload;

        return $this;
    }

    protected function action(): array
    {
        $parameters = [
            'flow_message_version' => $this->flowMessageVersion,
            'flow_id' => $this->flowId,
            'flow_cta' => $this->flowCta,
            'flow_action' => $this->flowAction,
        ];

        if ($this->flowToken !== null) {
            $parameters['flow_token'] = $this->flowToken;
        }

        if ($this->flowActionPayload !== null) {
            $parameters['flow_action_payload'] = $this->flowActionPayload;
        }

        $interactive = [
            'type' => InteractiveType::FLOW,
            'body' => [
                'text' => $this->getBody(),
            ],
            'action' => [
                'name' => InteractiveType::FLOW,
                'parameters' => $parameters,
            ],
        ];

        if ($this->hasHeader()) {
            $interactive = array_merge($interactive, $this->getHeader());
        }

        if ($this->hasFooter()) {
            $interactive = array_merge($interactive, [
                'footer' => [
                    'text' => $this->getFooter(),
                ]
            ]);
        }

        return [
            'type' => 'interactive',
            'interactive' => $interactive,
        ];
    }
}
