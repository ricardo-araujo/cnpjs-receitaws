<?php

use Interop\Container\ContainerInterface;

return [

    \GuzzleHttp\ClientInterface::class => function() {

    return new \GuzzleHttp\Client([
        'cookies' => true,
        'verify' => false,
        'timeout' => 5,
        'connect_timeout' => 5]);
    },

    \Psr\Log\LoggerInterface::class => function(ContainerInterface $c) {

        $fmtr = new \Monolog\Formatter\LineFormatter(null, 'Y-m-d H:i:s.u', false, true);
        $sh = new \Monolog\Handler\StreamHandler($c->get('log.path'));
        $logger = new \Monolog\Logger('ReceitaWS');

        $sh->setFormatter($fmtr);
        $logger->pushHandler($sh);

        return $logger;
    }
];