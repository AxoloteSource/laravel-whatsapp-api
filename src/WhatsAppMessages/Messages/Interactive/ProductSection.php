<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive;

use Exception;

class ProductSection
{
    private string $title;
    private array $productItems = [];

    public function __construct(string $title)
    {
        if (strlen($title) > 24) {
            throw new Exception("Section title is too long. Max length is 24");
        }
        $this->title = $title;
    }

    public static function create(string $title): ProductSection
    {
        return new self($title);
    }

    public function addProduct(ProductItem $product): ProductSection
    {
        $this->productItems[] = $product->toArray();

        return $this;
    }

    public function addProducts(array $products): ProductSection
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'product_items' => $this->productItems,
        ];
    }
}
