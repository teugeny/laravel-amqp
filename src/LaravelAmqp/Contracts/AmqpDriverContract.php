<?php

namespace Zweck\LaravelAmqp\Contracts;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Interface AmqpDriverContract
 * @package LaravelAmqp\Contracts
 */
interface AmqpDriverContract
{
    /**
     * @return mixed
     */
    public function getConnection(): AMQPStreamConnection;

    /**
     * @return mixed
     */
    public function getChannel(): AMQPChannel;
}