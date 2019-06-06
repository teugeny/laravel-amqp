<?php

namespace Zweck\LaravelAmqp\Services;

use Zweck\LaravelAmqp\Entities\AmqpNotify;
use PhpAmqpLib\Message\AMQPMessage;
use Zweck\LaravelAmqp\Contracts\AmqpDriverContract;

/**
 * Class AmqpService
 * @package LaravelAmqp\Services
 */
class AmqpService
{
    /**
     * @var AmqpDriverContract
     */
    private static $driver;

    /**
     * AmqpService constructor.
     *
     * @param AmqpDriverContract $driver
     */
    public function __construct(AmqpDriverContract $driver)
    {
        self::$driver = $driver;
    }


    /**
     * @return mixed|\PhpAmqpLib\Connection\AMQPStreamConnection
     */
    public function getConnection()
    {
        return self::$driver->getConnection();
    }

    /**
     * @return mixed|\PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        return self::$driver->getChannel();
    }

    /**
     * @param AmqpNotify $message
     */
    public function send(AmqpNotify $message): void
    {
        $connection = $this->getConnection();

        /** @var  $channel */
        $channel = $connection->channel();

        $channel->queue_declare($message->getRoutingKey(), false, false, false, false);

        $channel->exchange_declare(
            $message->getExchange(),
            $message->getExchangeType(),
            $message->isPassive(),
            $message->isDurable(),
            $message->isAutoDelete()
        );

        $msg = new AMQPMessage($message->getPayload());

        $channel->basic_publish($msg, '', $message->getRoutingKey());
    }
}