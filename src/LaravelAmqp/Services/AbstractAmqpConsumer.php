<?php

namespace Zweck\LaravelAmqp\Services;

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Console\Command;

/**
 * Class AbstractAmqpConsumer
 * @package LaravelAmqp\Services
 */
abstract class AbstractAmqpConsumer extends Command
{
    /**
     * @var AmqpService
     */
    private $service;

    /**
     * @var
     */
    private $routingKey;

    /**
     * @var bool
     */
    private $pasive = false;

    /**
     * @var bool
     */
    private $durable = false;

    /**
     * @var bool
     */
    private $exclusive = false;

    /**
     * @var bool
     */
    private $autoDelete = false;

    /**
     * @var string
     */
    private $consumerTag = '';

    /**
     * @var bool
     */
    private $noLocal = false;

    /**
     * @var bool
     */
    private $noAck = true;

    /**
     * @var bool
     */
    private $noWait = false;

    /**
     * @var string
     */
    private $exchange = '';

    /**
     * @var string
     */
    private $exchangeType = AMQPExchangeType::TOPIC;

    /**
     * AbstractAmqpConsumer constructor.
     */
    public function __construct(AmqpService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * @return bool
     */
    public function isPasive(): bool
    {
        return $this->pasive;
    }

    /**
     * @param bool $pasive
     */
    public function setPasive(bool $pasive): void
    {
        $this->pasive = $pasive;
    }

    /**
     * @return bool
     */
    public function isDurable(): bool
    {
        return $this->durable;
    }

    /**
     * @param bool $durable
     */
    public function setDurable(bool $durable): void
    {
        $this->durable = $durable;
    }

    /**
     * @return bool
     */
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     */
    public function setExclusive(bool $exclusive): void
    {
        $this->exclusive = $exclusive;
    }

    /**
     * @return bool
     */
    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    /**
     * @param bool $autoDelete
     */
    public function setAutoDelete(bool $autoDelete): void
    {
        $this->autoDelete = $autoDelete;
    }

    /**
     * @return string
     */
    public function getConsumerTag(): string
    {
        return $this->consumerTag;
    }

    /**
     * @param string $consumerTag
     */
    public function setConsumerTag(string $consumerTag): void
    {
        $this->consumerTag = $consumerTag;
    }

    /**
     * @return bool
     */
    public function isNoLocal(): bool
    {
        return $this->noLocal;
    }

    /**
     * @param bool $noLocal
     */
    public function setNoLocal(bool $noLocal): void
    {
        $this->noLocal = $noLocal;
    }

    /**
     * @return bool
     */
    public function isNoAck(): bool
    {
        return $this->noAck;
    }

    /**
     * @param bool $noAck
     */
    public function setNoAck(bool $noAck): void
    {
        $this->noAck = $noAck;
    }

    /**
     * @return bool
     */
    public function isNoWait(): bool
    {
        return $this->noWait;
    }

    /**
     * @param bool $noWait
     */
    public function setNoWait(bool $noWait): void
    {
        $this->noWait = $noWait;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    public function setRoutingKey($key)
    {
        $this->routingKey = $key;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     */
    public function setExchange(string $exchange): void
    {
        $this->exchange = $exchange;
    }

    /**
     * @return string
     */
    public function getExchangeType(): string
    {
        return $this->exchangeType;
    }

    /**
     * @param string $exchangeType
     */
    public function setExchangeType(string $exchangeType): void
    {
        $this->exchangeType = $exchangeType;
    }

    /**
     * @throws \ErrorException
     */
    public function listen(): void
    {
        $connection = $this->service->getConnection();

        $channel = $connection->channel();

        $channel->queue_declare($this->routingKey, $this->pasive, $this->durable, $this->exclusive, $this->autoDelete);
        $channel->exchange_declare(
            $this->getExchange(),
            $this->getExchangeType(),
            $this->isPasive(),
            $this->isDurable(),
            $this->isAutoDelete()
        );

        $channel->basic_consume(
            $this->routingKey,
            $this->consumerTag,
            $this->noLocal,
            $this->noAck,
            $this->exclusive,
            $this->noWait,
            [$this, 'fire']
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    /**
     * Setup routing key, exhange and start listen
     */
    abstract public function handle(): void;

    /**
     * Method wich catch message from AMQP
     *
     * @param AMQPMessage $message
     *
     * @return mixed
     */
    abstract public function fire(AMQPMessage $message);

    /**
     * AMQP Message process logic
     *
     * @return mixed
     */
    abstract public function process();
}