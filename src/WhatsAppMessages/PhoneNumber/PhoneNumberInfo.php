<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\DTO\PhoneNumberDTO;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PhoneNumberInfo
{
    use Config;

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Get phone number information including quality rating and messaging limit tier
     *
     * @return PhoneNumberDTO
     * @throws ConnectionException
     */
    public function get(): PhoneNumberDTO
    {
        if (WhatsAppMessages::isFake()) {
            $this->initializeFakeResponse();
        }

        $url = "$this->baseUrl$this->phoneNumberID";
        $queryParams = [
            'fields' => 'quality_rating,messaging_limit_tier,verified_name,quality_score',
        ];

        $url = $this->addQueryParams($url, $queryParams);

        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->bearer",
            'Content-Type' => 'application/json',
        ])->get($url);

        return new PhoneNumberDTO($response->json());
    }

    private function addQueryParams(string $url, array $params): string
    {
        $parts = parse_url($url);

        $query = [];

        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        $query = array_merge($query, $params);

        $parts['query'] = http_build_query($query);

        return
            ($parts['scheme'] ?? '') . (isset($parts['scheme']) ? '://' : '') .
            ($parts['host'] ?? '') .
            ($parts['path'] ?? '') .
            (!empty($parts['query']) ? '?' . $parts['query'] : '');
    }

    private function initializeFakeResponse(): void
    {
        Http::fake([
            '*' => Http::response([
                'id' => '123456789',
                'verified_name' => 'Mi Empresa',
                'quality_rating' => 'GREEN',
                'quality_score' => 0,
                'messaging_limit_tier' => 'TIER_1000',
            ]),
        ]);
    }
}
