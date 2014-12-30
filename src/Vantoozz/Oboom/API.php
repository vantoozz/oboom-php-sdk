<?php

namespace Vantoozz\Oboom;

use Vantoozz\Oboom\Transport\TransportInterface;
use InvalidArgumentException;

/**
 * Class API
 * @package Vantoozz\Oboom
 */
class API
{
    const ENDPOINT_WWW = 'www.oboom.com';
    const ENDPOINT_API = 'api.oboom.com';
    const ENDPOINT_UPLOAD = 'upload.oboom.com';

    private $endpoints = [
        self::ENDPOINT_API,
        self::ENDPOINT_WWW,
        self::ENDPOINT_UPLOAD,
    ];

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @param TransportInterface $transport
     * @param Auth               $auth
     */
    public function __construct(TransportInterface $transport, Auth $auth)
    {
        $this->transport = $transport;
        $this->auth      = $auth;
    }

    /**
     * @param       $endpoint
     * @param       $method
     * @param array $params
     *
     * @return mixed
     */
    public function call($endpoint, $method, array $params = [])
    {
        if (!in_array($endpoint, $this->endpoints)) {
            throw new InvalidArgumentException('No such endpoint');
        }

        $params['token'] = $this->auth->getToken();

        return $this->transport->call('https://' . $endpoint . $method, $params);
    }
}
