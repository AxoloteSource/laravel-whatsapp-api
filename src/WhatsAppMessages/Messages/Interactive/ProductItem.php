<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

class ProductItem
{
    private string $productRetailerId;

    public function __construct(string $productRetailerId)
    {
        $this->productRetailerId = $productRetailerId;
    }

    public static function create(string $productRetailerId): ProductItem
    {
        return new self($productRetailerId);
    }

    public function getProductRetailerId(): string
    {
        return $this->productRetailerId;
    }

    public function toArray(): array
    {
        return [
            'product_retailer_id' => $this->productRetailerId,
        ];
    }
}
