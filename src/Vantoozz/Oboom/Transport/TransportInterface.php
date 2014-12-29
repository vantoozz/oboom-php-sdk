<?php

namespace Vantoozz\Oboom\Transport;


/**
 * Interface TransportInterface
 * @package Vantoozz\Oboom\Transport
 */
interface TransportInterface
{

    /**
     * @param $url
     * @param $params
     *
     * @return mixed
     */
    public function call($url, array $params = []);
}