<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Config;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums\PhoneNumberFieldEnum;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\PhoneNumber\DTO\PhoneNumberDTO;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class PhoneNumberInfo
{
    use Config;

    protected array $fields = [];

    public function __construct()
    {
        $this->initialize();
    }

    public function select(array $fields): self
    {
        $validFields = PhoneNumberFieldEnum::all();
        foreach ($fields as $field) {
            if (!in_array($field, $validFields)) {
                throw new InvalidArgumentException("Invalid field: $field");
            }
        }

        $this->fields = $fields;
        return $this;
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

        if (!empty($this->fields)) {
            $queryParams = [
                'fields' => implode(',', $this->fields),
            ];
            $url = $this->addQueryParams($url, $queryParams);
        }

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
                'quality_score' => [
                    'score' => 'GREEN',
                ],
                'code_verification_status' => 'EXPIRED',
                'display_phone_number' => '+52 1 33 3781 5545',
                'platform_type' => 'CLOUD_API',
                'throughput' => [
                    'level' => 'STANDARD',
                ],
            ]),
        ]);
    }
}
