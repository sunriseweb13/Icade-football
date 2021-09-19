<?php

declare(strict_types=1);

namespace App\Api;

class ApiSearch implements ApiSearchInterface
{
    private ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

	public function search(string $path, array $parameters = []): array
	{
		unset($parameters['round']);
		
		$results = $this->client->get(sprintf('%s?%s', $path, http_build_query(array_filter($parameters))));

		if (0 !== count($results['errors'])) {
			throw new \RuntimeException(implode(',', $results['errors']));
		}
		
		return $results['response'];
	}
}