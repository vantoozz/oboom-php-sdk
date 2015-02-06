<?php

namespace Vantoozz\Oboom\Transport;

use Exception;
use GuzzleHttp\Client;
use RuntimeException;

/**
 * Class GuzzleTransport
 * @package Vantoozz\Oboom\Transport
 */
class GuzzleTransport extends AbstractTransport
{
    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param       $url
     * @param array $params
     *
     * @return mixed
     * @throws RuntimeException
     */
    public function call($url, array $params = [])
    {
        try {
            $response = $this->guzzle->get($url, [
                'query' => $params
            ]);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        $response = $response->json();

        if (!is_array($response)) {
            throw new RuntimeException('Bad response: ' . $response);
        }

        if (200 !== $response[0] and isset($response[1])) {
            throw new RuntimeException($response[1], $response[0]);
        }

        if (200 !== $response[0]) {
            throw new RuntimeException('API error', $response[0]);
        }

        return $response;
    }
}
