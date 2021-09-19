<?php

declare(strict_types=1);

namespace App\Api;

interface ApiSearchInterface
{
	public function search(string $path, array $parameters = []): array;
}