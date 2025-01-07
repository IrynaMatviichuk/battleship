<?php

namespace App\Providers;

use Battleship\Domain\BoardRepository;
use Battleship\Infrastructure\DoctrineBoardRepository;
use Illuminate\Support\ServiceProvider;

class BattleshipsServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->singleton(BoardRepository::class, DoctrineBoardRepository::class);
    }
}
