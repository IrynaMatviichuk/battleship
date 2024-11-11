<?php

namespace App\Providers;

use Battleship\Shared\CommandBus;
use Battleship\Shared\Messaging\CommandHandlerMiddleware;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->singleton(CommandBus::class, CommandHandlerMiddleware::class);
    }
}
