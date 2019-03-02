<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMQMessanger
{
    private $connectionData;
    protected $queueName;

    /** @var AMQPStreamConnection */
    protected $connection = null;

    /** @var AMQPChannel */
    protected $channel = null;

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
