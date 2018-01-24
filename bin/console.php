<?php

require_once __DIR__ . '/../Bootstrap.php';

use Symfony\Component\Console\Application;

$app = new Application('New Empresometro', '1.0.0');

$app->add($container->get(\Forseti\Cnpjs\Command\CapturaEmpresaCommand::class));
$app->add($container->get(\Forseti\Cnpjs\Command\CapturaTodosCnpjsCommand::class));
$app->add($container->get(\Forseti\Cnpjs\Command\CapturaEmpresasFromTableCommand::class));

$app->run();