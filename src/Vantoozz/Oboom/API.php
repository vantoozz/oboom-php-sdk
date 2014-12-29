<?php

namespace Vantoozz\Oboom;

use Vantoozz\Oboom\Transport\TransportInterface;

/**
 * Class API
 * @package Vantoozz\Oboom
 */
class API
{
    const ENDPOINT_WWW = 'www.oboom.com';

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
     * @param       $method
     * @param array $params
     *
     * @return mixed
     */
    public function call($method, array $params = [])
    {
        $params['token'] = $this->auth->getToken();

        return $this->transport->call('https://' . self::ENDPOINT_WWW . $method, $params);
    }
}
