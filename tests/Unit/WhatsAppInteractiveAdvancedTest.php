<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\CatalogMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\FlowMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\ProductItem;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\ProductListMessage;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Interactive\ProductSection;
use Exception;

class WhatsAppInteractiveAdvancedTest extends TestCase
{
    public function test_can_create_catalog_message()
    {
        $catalog = WhatsAppMessages::catalog('521234567890');
        $this->assertInstanceOf(CatalogMessage::class, $catalog);
    }

    public function test_catalog_message_to_array_basic()
    {
        $catalog = WhatsAppMessages::catalog('521234567890')
            ->body('Mira nuestro catálogo');

        $payload = $catalog->toArray();

        $this->assertEquals('interactive', $payload['type']);
        $this->assertEquals('catalog_message', $payload['interactive']['type']);
        $this->assertEquals('Mira nuestro catálogo', $payload['interactive']['body']['text']);
        $this->assertEquals('catalog_message', $payload['interactive']['action']['name']);
        $this->assertArrayHasKey('parameters', $payload['interactive']['action']);
    }

    public function test_catalog_message_to_array_with_thumbnail()
    {
        $catalog = WhatsAppMessages::catalog('521234567890')
            ->body('Mira nuestro catálogo')
            ->thumbnailProductRetailerId('prod-123');

        $payload = $catalog->toArray();

        $this->assertEquals('prod-123', $payload['interactive']['action']['parameters']['thumbnail_product_retailer_id']);
    }

    public function test_catalog_message_to_array_with_header_and_footer()
    {
        $catalog = WhatsAppMessages::catalog('521234567890')
            ->setHeaderText('Catálogo de productos')
            ->body('Mira nuestro catálogo')
            ->footer('¡Precios sujetos a cambio!');

        $payload = $catalog->toArray();

        $this->assertEquals('Catálogo de productos', $payload['interactive']['header']['text']);
        $this->assertEquals('text', $payload['interactive']['header']['type']);
        $this->assertEquals('¡Precios sujetos a cambio!', $payload['interactive']['footer']['text']);
    }

    public function test_catalog_message_includes_recipient()
    {
        $catalog = WhatsAppMessages::catalog('521234567890')->body('Mira nuestro catálogo');
        $payload = $catalog->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_can_create_product_list_message()
    {
        $list = WhatsAppMessages::productList('521234567890', 'catalog_abc');
        $this->assertInstanceOf(ProductListMessage::class, $list);
    }

    public function test_product_item_to_array()
    {
        $item = ProductItem::create('product-1');
        $this->assertEquals(['product_retailer_id' => 'product-1'], $item->toArray());
    }

    public function test_product_section_to_array()
    {
        $section = ProductSection::create('Electronics')
            ->addProduct(ProductItem::create('laptop-1'))
            ->addProduct(ProductItem::create('phone-1'));

        $array = $section->toArray();

        $this->assertEquals('Electronics', $array['title']);
        $this->assertCount(2, $array['product_items']);
        $this->assertEquals('laptop-1', $array['product_items'][0]['product_retailer_id']);
        $this->assertEquals('phone-1', $array['product_items'][1]['product_retailer_id']);
    }

    public function test_product_section_add_products_array()
    {
        $section = ProductSection::create('All products')
            ->addProducts([
                ProductItem::create('a'),
                ProductItem::create('b'),
                ProductItem::create('c'),
            ]);

        $this->assertCount(3, $section->toArray()['product_items']);
    }

    public function test_product_section_throws_exception_for_long_title()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Section title is too long. Max length is 24");

        ProductSection::create(str_repeat('a', 25));
    }

    public function test_product_list_message_to_array_with_one_section()
    {
        $section = ProductSection::create('Featured')
            ->addProduct(ProductItem::create('item-1'))
            ->addProduct(ProductItem::create('item-2'));

        $list = WhatsAppMessages::productList('521234567890', 'catalog_xyz')
            ->setHeaderText('Top productos')
            ->body('Mira nuestros productos destacados')
            ->footer('Stock limitado')
            ->addSection($section);

        $payload = $list->toArray();

        $this->assertEquals('interactive', $payload['type']);
        $this->assertEquals('product_list', $payload['interactive']['type']);
        $this->assertEquals('catalog_xyz', $payload['interactive']['action']['catalog_id']);
        $this->assertCount(1, $payload['interactive']['action']['sections']);
        $this->assertEquals('Featured', $payload['interactive']['action']['sections'][0]['title']);
        $this->assertCount(2, $payload['interactive']['action']['sections'][0]['product_items']);
        $this->assertEquals('Top productos', $payload['interactive']['header']['text']);
        $this->assertEquals('Mira nuestros productos destacados', $payload['interactive']['body']['text']);
        $this->assertEquals('Stock limitado', $payload['interactive']['footer']['text']);
    }

    public function test_product_list_message_to_array_with_multiple_sections()
    {
        $section1 = ProductSection::create('Clothing')->addProduct(ProductItem::create('shirt'));
        $section2 = ProductSection::create('Shoes')->addProduct(ProductItem::create('sneaker'));

        $list = WhatsAppMessages::productList('521234567890', 'cat')
            ->body('Mira')
            ->addSection($section1)
            ->addSection($section2);

        $payload = $list->toArray();

        $this->assertCount(2, $payload['interactive']['action']['sections']);
    }

    public function test_product_list_message_throws_exception_without_sections()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("At least one product section is required");

        WhatsAppMessages::productList('521234567890', 'cat')->body('Test')->toArray();
    }

    public function test_product_list_message_includes_recipient()
    {
        $section = ProductSection::create('A')->addProduct(ProductItem::create('p1'));
        $list = WhatsAppMessages::productList('521234567890', 'cat')
            ->body('Products')
            ->addSection($section);

        $payload = $list->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_can_create_flow_message()
    {
        $flow = WhatsAppMessages::flow('521234567890', 'flow_abc');
        $this->assertInstanceOf(FlowMessage::class, $flow);
    }

    public function test_flow_message_to_array_basic()
    {
        $flow = WhatsAppMessages::flow('521234567890', 'flow_abc')
            ->body('Inicia el flujo');

        $payload = $flow->toArray();

        $this->assertEquals('interactive', $payload['type']);
        $this->assertEquals('flow', $payload['interactive']['type']);
        $this->assertEquals('flow', $payload['interactive']['action']['name']);
        $this->assertEquals('Inicia el flujo', $payload['interactive']['body']['text']);
    }

    public function test_flow_message_to_array_with_all_options()
    {
        $flow = WhatsAppMessages::flow('521234567890', 'flow_xyz')
            ->setHeaderText('Encuesta')
            ->body('Cuéntanos tu opinión')
            ->footer('Toma 1 minuto')
            ->flowToken('token-123')
            ->flowCta('Empezar')
            ->flowMessageVersion('3')
            ->flowAction(FlowMessage::FLOW_ACTION_NAVIGATE)
            ->flowActionPayload(['screen' => 'FIRST_SCREEN']);

        $payload = $flow->toArray();

        $params = $payload['interactive']['action']['parameters'];
        $this->assertEquals('flow_xyz', $params['flow_id']);
        $this->assertEquals('token-123', $params['flow_token']);
        $this->assertEquals('Empezar', $params['flow_cta']);
        $this->assertEquals('3', $params['flow_message_version']);
        $this->assertEquals('navigate', $params['flow_action']);
        $this->assertEquals(['screen' => 'FIRST_SCREEN'], $params['flow_action_payload']);
    }

    public function test_flow_message_to_array_with_data_exchange_action()
    {
        $flow = WhatsAppMessages::flow('521234567890', 'flow_data')
            ->body('Sync data')
            ->flowAction(FlowMessage::FLOW_ACTION_DATA_EXCHANGE);

        $payload = $flow->toArray();

        $this->assertEquals('data_exchange', $payload['interactive']['action']['parameters']['flow_action']);
    }

    public function test_flow_message_includes_recipient()
    {
        $flow = WhatsAppMessages::flow('521234567890', 'flow_id')->body('Start');
        $payload = $flow->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_flow_message_constants()
    {
        $this->assertEquals('navigate', FlowMessage::FLOW_ACTION_NAVIGATE);
        $this->assertEquals('data_exchange', FlowMessage::FLOW_ACTION_DATA_EXCHANGE);
    }

    public function test_messages_supports_fluent_chaining_all_three()
    {
        $catalog = WhatsAppMessages::catalog('521234567890')
            ->setHeaderText('Header')
            ->body('Body')
            ->footer('Footer')
            ->thumbnailProductRetailerId('prod-1');

        $this->assertInstanceOf(CatalogMessage::class, $catalog);

        $list = WhatsAppMessages::productList('521234567890', 'cat')
            ->setHeaderText('Header')
            ->body('Body')
            ->footer('Footer')
            ->addSection(ProductSection::create('A')->addProduct(ProductItem::create('p1')));

        $this->assertInstanceOf(ProductListMessage::class, $list);

        $flow = WhatsAppMessages::flow('521234567890', 'flow')
            ->setHeaderText('Header')
            ->body('Body')
            ->footer('Footer')
            ->flowToken('token')
            ->flowCta('Go')
            ->flowActionPayload(['screen' => 'S1']);

        $this->assertInstanceOf(FlowMessage::class, $flow);
    }
}
