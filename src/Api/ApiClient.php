<?php

declare(strict_types=1);

namespace App\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private string $host;
    private string $apiKey;
    private HttpClientInterface $client;

    public function __construct(string $host, string $apiKey, HttpClientInterface $client)
    {
        if (empty($apiKey)) {
            throw new \RuntimeException("API key required");
        }
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function get(string $endpoint): array
    {
        return $this->api($endpoint, [], 'GET');
    }

    private function api(string $endpoint, array $data = [], string $method = 'POST'): array
    {
        $response = $this->client->request($method, sprintf('https://%s/%s', $this->host, $endpoint), [
            'json' => $data,
            'headers' => [
                'x-rapidapi-host' => $this->host,
                'x-rapidapi-key' => $this->apiKey,
            ],
        ]);
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode($response->getContent(), true);
        }
        throw new ApiException($response);
    }
}