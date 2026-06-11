<?php

namespace Axolotesource\LaravelWhatsappApi\Tests;

use Axolotesource\LaravelWhatsappApi\LaravelWhatsappApiServiceProvider;
use Illuminate\Http\JsonResponse;
use Mockery;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelWhatsappApiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-whatsapp-api.api', 'https://graph.facebook.com/v17.0/');
        $app['config']->set('laravel-whatsapp-api.phone_number_id', '123456789');
        $app['config']->set('laravel-whatsapp-api.bearer', 'fake-token');
        $app['config']->set('laravel-whatsapp-api.account_id', '987654321');
        $app['config']->set('laravel-whatsapp-api.test_mode', false);
    }

    protected function createMockJsonResponse(array $data, int $status): JsonResponse
    {
        $response = Mockery::mock(JsonResponse::class);
        $response->shouldReceive('getStatusCode')->andReturn($status);
        $response->shouldReceive('getData')->andReturnUsing(function ($assoc = false) use ($data) {
            return $assoc ? $data : (object) $data;
        });

        return $response;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
