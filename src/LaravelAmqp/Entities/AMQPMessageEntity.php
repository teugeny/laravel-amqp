<?php

namespace Zweck\LaravelAmqp\Entities;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class AMQPMessageEntity
 * @package App\Entities
 */
class AMQPMessageEntity
{
    /**
     * @var
     */
    private $messageKey;

    /**
     * @var
     */
    private $payload;

    /**
     * @var ConsoleOutput
     */
    private $logger;

    /**
     * AMQPMessageEntity constructor.
     */
    public function __construct()
    {
        $this->logger = new ConsoleOutput();
    }

    /**
     * @return mixed
     */
    public function getMessageKey()
    {
        return $this->messageKey;
    }

    /**
     * @param mixed $messageKey
     */
    public function setMessageKey($messageKey): void
    {
        $this->messageKey = $messageKey;
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
     * @return string
     */
    public function encode(): string
    {
        return json_encode(
            [
                'messageKey' => $this->getMessageKey(),
                'payload'    => $this->getPayload(),
            ]
        );
    }

    /**
     * @param AMQPMessage $message
     *
     * @return AMQPMessageEntity
     */
    public function decode(AMQPMessage $message): AMQPMessageEntity
    {
        $json = $message->getBody();
        $data = json_decode($json);

        if (isset($data->messageKey)) {
            $this->setMessageKey($data->messageKey);
        }

        if (isset($data->payload)) {
            $this->setPayload($data->payload);
        }

        return $this;
    }

    /**
     * Set message catch
     */
    public function catch(): void
    {
        $this->logger->writeln("{$this->getMessageKey()} catch");
    }

    /**
     * Set message in process
     */
    public function inProcess(): void
    {
        $this->logger->writeln("{$this->getMessageKey()} in process");
    }

    /**
     * Set message is failed
     */
    public function fail(): void
    {
        $this->logger->writeln("{$this->getMessageKey()} failed");
    }

    /**
     * Set message is done
     */
    public function done(): void
    {
        $this->logger->writeln("{$this->getMessageKey()} done");
    }
}