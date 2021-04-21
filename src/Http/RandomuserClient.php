<?php


namespace App\Http;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class RandomuserClient
 */
class RandomuserClient implements CustomerApiClientInterface
{
    /** @var \Symfony\Contracts\HttpClient\HttpClientInterface */
    private HttpClientInterface $httpClient;

    /** @var string */
    private const URL = 'https://randomuser.me/api?results';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param int $multiple
     * @param string $nationality
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchCustomers(int $multiple, string $nationality): JsonResponse
    {
        $response = $this->httpClient->request('GET', self::URL,
            [
                'query' => [
                    'results' => $multiple,
                    'nat' => $nationality,
                ],
            ]
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return new JsonResponse('Error api client', Response::HTTP_BAD_REQUEST);
        }

        $content = json_decode($response->getContent(), true);

        return new JsonResponse($content['results'], Response::HTTP_OK);
    }
}