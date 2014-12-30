<?php

namespace Vantoozz\Oboom;

use InvalidArgumentException;
use RuntimeException;
use Vantoozz\Oboom\Transport\TransportInterface;

/**
 * Class Auth
 * @package Vantoozz\Oboom
 */
class Auth
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @param TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        foreach (array('username', 'password') as $param) {
            if (empty($config[$param])) {
                throw new InvalidArgumentException('No ' . $param . ' given');
            }
            $this->$param = $config[$param];
        }

        $this->password = $this->hashPassword($this->password);

        return $this;
    }

    /**
     * @return $this
     */
    public function login()
    {
        $data = $this->transport->call('https://' . API::ENDPOINT_WWW . '/1/login', [
            'auth'   => $this->username,
            'pass'   => $this->password,
            'source' => '/#app'
        ]);

        if (empty($data[1]['session'])) {
            throw new RuntimeException('API error');
        }

        $this->token = $data[1]['session'];

        return $this;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    private function hashPassword($password)
    {
        return hash_pbkdf2('sha1', $password, strrev($password), 1000, 32);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
