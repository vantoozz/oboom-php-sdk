<?php

namespace Vantoozz\Oboom;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class API
 * @package Vantoozz\Oboom
 */
class API
{
    const ENDPOINT_WWW = 'www.oboom.com';

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $session;

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
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        foreach (array('username', 'password', 'source') as $param) {
            if (empty($config[$param])) {
                throw new InvalidArgumentException('No ' . $param . ' given');
            }
            $this->$param = $config[$param];
        }

        $this->password = $this->hashPassword($this->password);

        return $this;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    private function hashPassword($password)
    {
        return hash_pbkdf2('sha1', $password, strrev($password), 1000, 16);
    }


    /**
     * @return $this
     */
    public function login()
    {
        $data = $this->call('/1/login', [
            'auth'   => $this->username,
            'pass'   => $this->password,
            'source' => $this->source
        ]);

        $this->session = $data['session'];

        return $this;
    }

    /**
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function call($method, $params)
    {

        try {
            $response = $this->guzzle->get('http://' . self::ENDPOINT_WWW . $method, [
                'query' => $params
            ]);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        $response = $response->json();

        if (200 !== $response[0]) {
            throw new RuntimeException($response[1]);
        }
        return $response[1];
    }
}
