<?php

namespace App\Infrastructure\Queues\Publisher;

use Bunny\Channel;
use Bunny\Client;
use Exception;
use React\Promise\PromiseInterface;

class RabbitMQPublisher implements PublisherInterface
{
    private Client $client;
    private string $queue;
    private PromiseInterface|Channel $channel;

    /**
     * @throws Exception
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->queue = getenv('QUEUE');

        $this->client->connect();
        $this->channel = $this->client->channel();

        $this->channel->queueDeclare($this->queue, durable: true);
    }

    public function publish(string $message): void
    {
        $this->channel->publish($message, routingKey: $this->queue);
    }
}
