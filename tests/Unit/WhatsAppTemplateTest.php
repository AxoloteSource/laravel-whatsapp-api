<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppTemplate;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums\TemplateStatus;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;

class WhatsAppTemplateTest extends TestCase
{
    public function test_it_can_be_instantiated()
    {
        $template = new WhatsAppTemplate();
        $this->assertInstanceOf(WhatsAppTemplate::class, $template);
    }

    public function test_it_can_set_limit()
    {
        $template = new WhatsAppTemplate();
        $template->limit(50);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('limit');
        $property->setAccessible(true);
        
        $this->assertEquals(50, $property->getValue($template));
    }

    public function test_it_can_add_where_filter()
    {
        $template = new WhatsAppTemplate();
        $template->where('status', TemplateStatus::APPROVED);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('params');
        $property->setAccessible(true);
        
        $params = $property->getValue($template);
        $this->assertEquals([TemplateStatus::APPROVED], $params['status']);
    }

    public function test_it_can_add_where_in_filter()
    {
        $template = new WhatsAppTemplate();
        $template->whereIn('status', [TemplateStatus::APPROVED, TemplateStatus::PENDING]);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('params');
        $property->setAccessible(true);
        
        $params = $property->getValue($template);
        $this->assertEquals([TemplateStatus::APPROVED, TemplateStatus::PENDING], $params['status']);
    }

    public function test_it_throws_exception_for_invalid_filter_field()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Filter field 'invalid_field' is not supported.");
        
        $template = new WhatsAppTemplate();
        $template->where('invalid_field', 'value');
    }

    public function test_it_throws_exception_for_invalid_status_value()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid status: INVALID_STATUS");
        
        $template = new WhatsAppTemplate();
        $template->where('status', 'INVALID_STATUS');
    }

    public function test_it_can_fetch_templates_with_fake_response()
    {
        WhatsAppMessages::fake();
        
        $template = new WhatsAppTemplate();
        $result = $template->get();
        
        $this->assertInstanceOf(\Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList::class, $result);
        $this->assertCount(1, $result->data());
        $this->assertEquals('hello_world', $result->data()->first()->name);
    }

    public function test_it_can_select_fields()
    {
        $template = new WhatsAppTemplate();
        $fields = ['name', 'status', 'id'];
        $template->select($fields);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('fields');
        $property->setAccessible(true);
        
        $this->assertEquals($fields, $property->getValue($template));
    }

    public function test_it_throws_exception_for_invalid_field_in_select()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid field: invalid_field");
        
        $template = new WhatsAppTemplate();
        $template->select(['name', 'invalid_field']);
    }

    public function test_it_can_call_limit_statically()
    {
        $template = WhatsAppTemplate::limit(25);
        $this->assertInstanceOf(WhatsAppTemplate::class, $template);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('limit');
        $property->setAccessible(true);
        
        $this->assertEquals(25, $property->getValue($template));
    }

    public function test_it_can_call_where_statically()
    {
        $template = WhatsAppTemplate::where('status', TemplateStatus::APPROVED);
        $this->assertInstanceOf(WhatsAppTemplate::class, $template);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('params');
        $property->setAccessible(true);
        
        $params = $property->getValue($template);
        $this->assertEquals([TemplateStatus::APPROVED], $params['status']);
    }

    public function test_it_can_call_where_in_statically()
    {
        $template = WhatsAppTemplate::whereIn('status', [TemplateStatus::APPROVED, TemplateStatus::PENDING]);
        $this->assertInstanceOf(WhatsAppTemplate::class, $template);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('params');
        $property->setAccessible(true);
        
        $params = $property->getValue($template);
        $this->assertEquals([TemplateStatus::APPROVED, TemplateStatus::PENDING], $params['status']);
    }

    public function test_it_can_call_select_statically()
    {
        $fields = ['name', 'status'];
        $template = WhatsAppTemplate::select($fields);
        $this->assertInstanceOf(WhatsAppTemplate::class, $template);
        
        $reflection = new \ReflectionClass($template);
        $property = $reflection->getProperty('fields');
        $property->setAccessible(true);
        
        $this->assertEquals($fields, $property->getValue($template));
    }

    public function test_it_can_call_get_statically()
    {
        WhatsAppMessages::fake();
        $result = WhatsAppTemplate::get();
        $this->assertInstanceOf(\Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList::class, $result);
    }

    public function test_it_can_call_list_statically()
    {
        WhatsAppMessages::fake();
        $result = WhatsAppTemplate::list();
        $this->assertInstanceOf(\Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList::class, $result);
    }

    public function test_it_can_chain_static_calls()
    {
        WhatsAppMessages::fake();
        $result = WhatsAppTemplate::select(['name', 'status'])
            ->limit(5)
            ->where('status', TemplateStatus::APPROVED)
            ->get();
            
        $this->assertInstanceOf(\Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList::class, $result);
    }

    public function test_it_can_all()
    {
        WhatsAppMessages::fake();
        $result = WhatsAppTemplate::all();
        $this->assertInstanceOf(\Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList::class, $result);
    }
}
