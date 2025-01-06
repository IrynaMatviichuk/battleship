<?php

namespace App\Providers;

use Battleship\Domain\BoardRepository;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Illuminate\Support\ServiceProvider;

class BattleshipsServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->singleton(BoardRepository::class, InMemoryBoardRepository::class);
    }
}
