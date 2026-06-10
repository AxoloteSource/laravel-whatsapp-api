<?php

namespace Axolotesource\LaravelWhatsappApi\WhatsAppMessages;

use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums\TemplateCategory;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums\TemplateFieldEnum;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Enums\TemplateStatus;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Templates\TemplateList;
use BadMethodCallException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class WhatsAppTemplate
{
    use Config;

    protected array $fields = [];

    protected int $limit = 10;

    protected array $params = [];
    private bool $isAll = false;

    /**
     * Handle static calls to the class
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (in_array($name, ['get', 'list', 'limit', 'select', 'where', 'whereIn', 'all'])) {
            return (new self())->$name(...$arguments);
        }

        throw new BadMethodCallException("Method $name does not exist.");
    }

    /**
     * Handle instance calls to the class
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (in_array($name, ['get', 'list', 'limit', 'select', 'where', 'whereIn', 'all'])) {
            return $this->$name(...$arguments);
        }

        throw new BadMethodCallException("Method $name does not exist.");
    }

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * List all message templates for the given WABA ID (account_id in config)
     *
     * @throws ConnectionException
     */
    public static function all(): TemplateList
    {
        $template = new WhatsAppTemplate();
        $template->isAll = true;

        return $template->get();
    }

    protected function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Add a filter to the request
     *
     * @param string $field
     * @param mixed $value
     * @return self
     * @throws InvalidArgumentException
     */
    protected function where(string $field, mixed $value): self
    {
        $this->validateFilter($field, $value);

        if (in_array($field, ['status', 'category'])) {
            $this->params[$field] = (array)$value;
        } else {
            $this->params[$field] = $value;
        }

        return $this;
    }

    /**
     * Add a filter with multiple values to the request
     *
     * @param string $field
     * @param array $values
     * @return self
     * @throws InvalidArgumentException
     */
    protected function whereIn(string $field, array $values): self
    {
        foreach ($values as $value) {
            $this->validateFilter($field, $value);
        }

        $this->params[$field] = $values;
        return $this;
    }

    /**
     * Validate the filter field and value
     *
     * @param string $field
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    protected function validateFilter(string $field, mixed $value): void
    {
        $allowedFilters = ['name', 'status', 'category', 'language'];
        if (!in_array($field, $allowedFilters)) {
            throw new InvalidArgumentException("Filter field '$field' is not supported.");
        }

        switch ($field) {
            case 'status':
                if (!in_array($value, TemplateStatus::all())) {
                    throw new InvalidArgumentException("Invalid status: $value");
                }
                break;
            case 'category':
                if (!in_array($value, TemplateCategory::all())) {
                    throw new InvalidArgumentException("Invalid category: $value");
                }
                break;
        }
    }

    /**
     * Set the fields to be returned by the API
     *
     * @param array $fields
     * @return self
     * @throws InvalidArgumentException
     */
    protected function select(array $fields): self
    {
        $validFields = TemplateFieldEnum::all();
        foreach ($fields as $field) {
            if (!in_array($field, $validFields)) {
                throw new InvalidArgumentException("Invalid field: $field");
            }
        }

        $this->fields = $fields;
        return $this;
    }

    /**
     * List all message templates for the given WABA ID (account_id in config)
     *
     * @return TemplateList
     * @throws ConnectionException
     */
    protected function get(): TemplateList
    {
        return $this->fetch();
    }

    /**
     * Alias for get()
     *
     * @return TemplateList
     * @throws ConnectionException
     */
    protected function list(): TemplateList
    {
        return $this->get();
    }

    /**
     * Fetch templates from a given URL or the default one
     *
     * @param string|null $url
     * @return TemplateList
     * @throws ConnectionException
     */
    public function fetch(?string $url = null): TemplateList
    {
        if (WhatsAppMessages::isFake()) {
            $this->initializeFakeResponse();
        }

        if (!$url) {
            $accountId = config('laravel-whatsapp-api.account_id');
            $url = "$this->baseUrl$accountId/message_templates";

            $queryParams = $this->params;

            if (! $this->isAll) {
                $queryParams['limit'] = $this->limit;
            }

            foreach ($queryParams as $key => $value) {
                if (is_array($value) && in_array($key, ['status', 'category'])) {
                    $queryParams[$key] = json_encode($value);
                }
            }

            if (!empty($this->fields)) {
                $queryParams['fields'] = implode(',', $this->fields);
            }

            $url = $this->addQueryParams($url, $queryParams);
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->bearer",
            'Content-Type' => "application/json"
        ])->get($url);

        return new TemplateList($response->json(), $this);
    }

    function addQueryParams(string $url, array $params): string
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
                        'name' => 'hello_world',
                        'status' => 'APPROVED',
                        'category' => 'UTILITY',
                        'language' => 'en_US',
                        'components' => [],
                        'id' => '123456789'
                    ]
                ],
                'paging' => [
                    'cursors' => [
                        'before' => 'MA==',
                        'after' => 'MQ=='
                    ]
                ]
            ]),
        ]);
    }
}
