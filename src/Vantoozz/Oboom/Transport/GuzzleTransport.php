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
     *
     */
    public function __construct()
    {
        $this->guzzle = new Client();
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

        if (200 !== $response[0]) {
            throw new RuntimeException($response[1], $response[0]);
        }

        return $response;
    }
}
