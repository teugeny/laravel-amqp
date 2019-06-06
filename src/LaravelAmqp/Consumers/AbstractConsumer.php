<?php

namespace Zweck\LaravelAmqp\Consumers;

use Illuminate\Support\Facades\Log;
use Zweck\LaravelAmqp\Entities\AMQPMessageEntity;
use Zweck\LaravelAmqp\Services\AbstractAmqpConsumer;
use Zweck\LaravelAmqp\Services\AmqpService;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

/**
 * Class ExtendConsumer
 * @package App\Consumers
 */
abstract class AbstractConsumer extends AbstractAmqpConsumer
{
    /**
     * @var AMQPMessageEntity
     */
    private $entity;

    /**
     * @var
     */
    private $params;

    /**
     * @var
     */
    private $payload;

    /**
     * ExtendConsumer constructor.
     *
     * @param AmqpService $service
     */
    public function __construct(AmqpService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param AMQPMessage $message
     *
     * @return mixed|void
     */
    public function fire(AMQPMessage $message)
    {
        $entity = new AMQPMessageEntity();
        $entity->decode($message);
        $this->setEntity($entity);

        $this->setPayload(json_decode($entity->getPayload()));

        $this->setParams(json_decode($entity->getMessageKey()));

        try {
            $entity->catch();
            $entity->inProcess();
            $this->process();
        } catch (Exception $exception) {
            $entity->fail();
            Log::error($this->getLoggerGroupName() .':'. $exception->getMessage());
        }
    }

    /**
     * @return AMQPMessageEntity
     */
    public function getEntity(): AMQPMessageEntity
    {
        return $this->entity;
    }

    /**
     * @param AMQPMessageEntity $entity
     */
    public function setEntity(AMQPMessageEntity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
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
     * Return name of the group and then show in log file
     *
     * @return string
     */
    abstract function getLoggerGroupName(): string;
}