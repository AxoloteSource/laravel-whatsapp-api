<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\DTO\TemplateDTO;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppTemplate;
use Illuminate\Support\Collection;

class TemplateList
{
    private Collection $data;
    private ?string $next;
    private ?string $previous;
    private WhatsAppTemplate $client;

    public function __construct(array $response, WhatsAppTemplate $client)
    {
        $this->data = collect($response['data'] ?? [])->map(function ($item) {
            return new TemplateDTO($item);
        });
        $this->next = $response['paging']['next'] ?? null;
        $this->previous = $response['paging']['previous'] ?? null;
        $this->client = $client;
    }

    public function data(): Collection
    {
        return $this->data;
    }

    public function hasNextPage(): bool
    {
        return !is_null($this->next);
    }

    public function hasPreviousPage(): bool
    {
        return !is_null($this->previous);
    }

    public function nextPage(): ?TemplateList
    {
        if (!$this->hasNextPage()) {
            return null;
        }
        return $this->client->fetch($this->next);
    }

    public function previousPage(): ?TemplateList
    {
        if (!$this->hasPreviousPage()) {
            return null;
        }
        return $this->client->fetch($this->previous);
    }
}
