<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\BusinessProfile\DTO\BusinessProfileDTO;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class BusinessProfileInfo
{
    use Config;

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Get the business profile information for the configured phone number
     *
     * @return BusinessProfileDTO
     * @throws ConnectionException
     */
    public function get(): BusinessProfileDTO
    {
        if (WhatsAppMessages::isFake()) {
            $this->initializeFakeResponse();
        }

        $url = "$this->baseUrl$this->phoneNumberID/whatsapp_business_profile";
        $queryParams = [
            'fields' => 'about,description,email,profile_picture_url,vertical,websites,address',
        ];

        $url = $this->addQueryParams($url, $queryParams);

        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->bearer",
            'Content-Type' => 'application/json',
        ])->get($url);

        $data = $response->json();
        $payload = $data['data'][0] ?? $data;

        return new BusinessProfileDTO($payload);
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
                'data' => [
                    [
                        'about' => 'About my business',
                        'description' => 'This is a longer description of the business',
                        'email' => 'contact@example.com',
                        'profile_picture_url' => 'https://example.com/photo.jpg',
                        'vertical' => 'RETAIL',
                        'websites' => [
                            'https://example.com',
                            'https://shop.example.com',
                        ],
                        'address' => '123 Main St, Mexico City',
                    ],
                ],
            ]),
        ]);
    }
}
