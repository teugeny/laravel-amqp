<?php

namespace Zweck\LaravelAmqp\Providers;

use Illuminate\Support\ServiceProvider;
use Zweck\LaravelAmqp\Drivers\RabbitMQDriver;
use Zweck\LaravelAmqp\Services\AmqpService;

/**
 * Class AmqpServiceProvider
 * @package LaravelAmqp\Providers
 */
class AmqpServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        //
    }

    /**
     *
     */
    public function register()
    {
        $this->app->singleton(
            AmqpService::class,
            function ($app) {
                $config = config('laravelamqp.use') != 'local'
                    ? config('laravelamqp.configurations.production')
                    : config('laravelamqp.configurations.local');

                return new AmqpService(new RabbitMQDriver($config));
            }
        );
    }
}