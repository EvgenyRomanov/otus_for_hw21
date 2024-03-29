<?php

use App\Domain\Repository\ApplicationFormInterface;
use App\Domain\Repository\StatusInterface;
use App\Infrastructure\Queues\Publisher\PublisherInterface;
use App\Infrastructure\Queues\Publisher\RabbitMQPublisher;
use App\Infrastructure\Repository\RepositoryApplicationFormDb;
use App\Infrastructure\Repository\RepositoryStatusDb;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Bunny\Client;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];

        $driver = $settings['driver'];
        $host = $settings['host'];
        $dbname = $settings['database'];
        $username = $settings['username'];
        $password = $settings['password'];
        $charset = $settings['charset'];
        $flags = $settings['flags'];
        $dsn = "$driver:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password, $flags);
    },

    Client::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['broker'];

        $host = $settings['host'];
        $vhost = $settings['vhost'];
        $user = $settings['user'];
        $password = $settings['password'];

        return new Client([
            'host' => $host,
            'vhost' => $vhost,
            'user' => $user,
            'password' => $password,
        ]);
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    PublisherInterface::class => function (ContainerInterface $container) {
        return new RabbitMQPublisher($container->get(Client::class));
    },

    ApplicationFormInterface::class => function (ContainerInterface $container) {
        return $container->get(RepositoryApplicationFormDb::class);
    },

    StatusInterface::class => function (ContainerInterface $container) {
        return $container->get(RepositoryStatusDb::class);
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool) $settings['display_error_details'],
            (bool) $settings['log_errors'],
            (bool) $settings['log_error_details']
        );
    },
];
