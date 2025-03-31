<?php

namespace App\Providers;

use Battleship\Shared\CommandBus;
use Battleship\Shared\FlushEntityManagerMiddleware;
use Battleship\Shared\Messaging\CommandHandlerMiddleware;
use Battleship\Shared\MiddlewareCommandBus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(CommandBus::class, static function ($app) {
            return new MiddlewareCommandBus(
                $app->make(FlushEntityManagerMiddleware::class),
                $app->make(CommandHandlerMiddleware::class),
            );
        });
    }
}
