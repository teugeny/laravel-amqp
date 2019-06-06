<?php

namespace Zweck\LaravelAmqp\Entities;

use PhpAmqpLib\Exchange\AMQPExchangeType;

/**
 * Class AmqpNotify
 * @package LaravelAmqp\Services
 */
class AmqpNotify
{
    /**
     * @var
     */
    private $routingKey;

    /**
     * @var
     */
    private $payload;

    /**
     * @var
     */
    private $queue;

    /**
     * @var
     */
    private $exchange;

    /**
     * @var bool
     */
    private $passive = false;

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
    private $exchangeType = AMQPExchangeType::TOPIC;

    /**
     * @return mixed
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @param mixed $routingKey
     */
    public function setRoutingKey($routingKey): void
    {
        $this->routingKey = $routingKey;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param mixed $queue
     */
    public function setQueue($queue): void
    {
        $this->queue = $queue;
    }

    /**
     * @return mixed
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param mixed $exchange
     */
    public function setExchange($exchange): void
    {
        $this->exchange = $exchange;
    }

    /**
     * @return bool
     */
    public function isPassive(): bool
    {
        return $this->passive;
    }

    /**
     * @param bool $passive
     */
    public function setPassive(bool $passive): void
    {
        $this->passive = $passive;
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
     * @param bool $autoDelete
     */
    public function setAutoDelete(bool $autoDelete): void
    {
        $this->autoDelete = $autoDelete;
    }
}