<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitMQMessanger.
 *
 * @psalm-suppress all
 */
abstract class RabbitMQMessanger
{
    protected $queueName;

    /** @var AMQPStreamConnection */
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;
    private $connectionData;

    public function __construct(RabbitMQConnection $connectionData, string $queueName)
    {
        $this->connectionData = $connectionData;
        $this->queueName = $queueName;
    }

    public function open(string $exchangeName = ''): void
    {
        if ($this->connection && $this->connection->isConnected()) {
            return;
        }

        $this->connection = new AMQPStreamConnection(
            $this->connectionData->host(),
            $this->connectionData->port(),
            $this->connectionData->user(),
            $this->connectionData->password()
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queueName, false, true, false, false);
    }

    public function close(string $exchangeName = ''): void
    {
        if (!$this->connection || !$this->connection->isConnected()) {
            return;
        }

        $this->channel->close();
        $this->connection->close();
    }
}
