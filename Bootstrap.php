<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/factory.php';

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

set_time_limit(0);
date_default_timezone_set('America/Sao_Paulo');

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/config.php');
$builder->addDefinitions(__DIR__ . '/app/factory.php');

$container = $builder->build();

$capsule = new Capsule();
$capsule->addConnection($container->get('mysql.options'), 'default');
$capsule->addConnection($container->get('mongodb.options'), 'mongodb');
$capsule->getDatabaseManager()->extend('mongodb', function($config) {
    return new \Jenssegers\Mongodb\Connection($config);
});
$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();