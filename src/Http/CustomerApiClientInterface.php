<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface CustomerApiClientInterface
 * @package App\Http
 */
interface CustomerApiClientInterface
{
    public function fetchCustomers(int $multiple, string $nationality): JsonResponse;
}