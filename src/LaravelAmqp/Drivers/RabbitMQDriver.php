<?php

namespace Zweck\LaravelAmqp\Drivers;

use PhpAmqpLib\Channel\AMQPChannel;
use Zweck\LaravelAmqp\Contracts\AmqpDriverContract;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitMQDriver
 * @package RabbitAmqp\Drivers
 */
class RabbitMQDriver implements AmqpDriverContract
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var array
     */
    private $config;

    /**
     * RabbitMQDriver constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config     = $config;
        $this->connection = $this->defineConnection();
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel
    {
        return $this->getConnection()->channel();
    }

    /**
     * @return AMQPStreamConnection
     */
    private function defineConnection()
    {
        return new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password']
        );
    }
}