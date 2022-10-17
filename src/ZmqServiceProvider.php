<?php

namespace Vikilaboy\LaravelZmq;

use Illuminate\Support\ServiceProvider;
use Vikilaboy\LaravelZmq\Connector\ZmqPublish;
use Vikilaboy\LaravelZmq\Connector\ZmqSubscribe;
use Vikilaboy\LaravelZmq\Broadcasting\Broadcaster\ZmqBroadcaster;

/**
 * Class ZmqServiceProvider
 * @package Vikilaboy\LaravelZmq
 */
class ZmqServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ .'/../config/zmq.php' => config_path('zmq.php')]);

        $this->app->make('Illuminate\Contracts\Broadcasting\Factory')
            ->extend('zmq', function ($app) {
                return new ZmqBroadcaster($this->app['zmq']);
            });
    }

    public function register()
    {
        $this->app->singleton('zmq', function ($app) {
            return new Zmq();
        });

        $this->app->singleton('zmq.connection.publish', function ($app) {
            return new ZmqPublish();
        });

        $this->app->singleton('zmq.connection.subscribe', function ($app) {
            return new ZmqSubscribe();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['zmq', 'zmq.connection.subscribe', 'zqm.connection.publish'];
    }
}
