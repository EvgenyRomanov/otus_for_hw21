<?php

use App\Infrastructure\Queues\Consumer\RabbitMQConsumer;
use DI\ContainerBuilder;

require_once __DIR__ . '/../../../../vendor/autoload.php';

try {
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->addDefinitions(__DIR__ . '/../../../../config/container.php');
    $container = $containerBuilder->build();
    $consumer= $container->get(RabbitMQConsumer::class);

    $consumer->run();
} catch (Exception $e) {
    print_r($e->getMessage());
}
