<?php

use Interop\Container\ContainerInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

return [

    \GuzzleHttp\ClientInterface::class => function() {

    return new \GuzzleHttp\Client([
        'cookies' => true,
        'verify' => false,
        'timeout' => 60,
        'connect_timeout' => 60
    ]);}
];