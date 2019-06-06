Laravel AMQP (RabbitMQ) - Simple

Plugin for work with queue throw RabbitMQ.

Create config file config/laravelamqp.php

with code

```
<?php
 
 return [
     'use' => env('APP_ENV'),
 
     'configurations' => [
         'production' => [
             'host'     => env('AMQP_HOST'),
             'port'     => env('AMQP_PORT'),
             'user'     => env('AMQP_USERNAME'),
             'password' => env('AMQP_PASSWORD'),
         ],
         'local' => [
             'host'     => env('AMQP_HOST'),
             'port'     => env('AMQP_PORT'),
             'user'     => env('AMQP_USERNAME'),
             'password' => env('AMQP_PASSWORD'),
         ]
     ],
 ];
 
```
 
 
 for send a message you can use
 
 ```
 <?php
 
 namespace App\Helpers;
 
 use Zweck\LaravelAmqp\Entities\AMQPMessageEntity;
 use App\Services\Traits\AmqpServiceGetter;
 use PhpAmqpLib\Exchange\AMQPExchangeType;
 use Zweck\LaravelAmqp\Entities\AmqpNotify;
 
 /**
  * Trait Publisher
  * @package App\Helpers
  */
 trait Publisher
 {
     use AmqpServiceGetter;
 
     /**
      * Example
      * 
      * @param array $params
      */
     public function testSome(array $params)
     {
         $this->notifyAMQP(
             'sms-messages',
             Json::encode(
                 [
                     'phone_number' => $params['phone_number'],
                     'country'      => $params['country'],
                     'code'         => $params['code'],
                 ]
             ),
             Json::encode(['task' => 'restore-sms']),
             'notification'
         );
     }
     
     /**
      * Method that will send your message to AMQP
      *
      * @param string $routingKey
      * @param string $payload
      * @param string $messageKey
      * @param string $exchange
      * @param string $exchangeType
      */
     public function notifyAMQP(
         string $routingKey,
         string $payload,
         string $messageKey,
         string $exchange = '',
         string $exchangeType = AMQPExchangeType::TOPIC
     ) {
         $notify = new AmqpNotify();
         $notify->setRoutingKey($routingKey);
 
         $entity = new AMQPMessageEntity();
         $entity->setPayload($payload);
         $entity->setMessageKey($messageKey);
         $notify->setExchange($exchange);
         $notify->setExchangeType($exchangeType);
 
         $notify->setPayload($entity->encode());
 
         $this->getAmqpService()->send($notify);
     }
 }

```


create your own consumer

```

<?php

namespace App\Consumers;

use Zweck\LaravelAmqp\Consumers\AbstractConsumer;
use App\Helpers\Sender;
use App\Helpers\Json;
use App\Models\DevicePush;
use Illuminate\Support\Facades\Cache;

/**
 * Class SimpleConsumer
 * @package App\Consumers
 */
class SimpleConsumer extends AbstractConsumer
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:simple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple consumer';

    /**
     * @return string
     */
    public function getLoggerGroupName(): string
    {
        return 'SIMPLE-MESSAGE';
    }

    /**
     * @throws \ErrorException
     */
    public function handle(): void
    {
        $this->setRoutingKey('sms-messages')->setExchange('notification');
        $this->listen();
    }

    /**
     * @return mixed|void
     */
    public function process()
    {
        $payload = $this->getPayload();

        // Do some staff

        $this->getEntity()->done();
    }
}

```