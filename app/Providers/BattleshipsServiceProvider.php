<?php

namespace App\Providers;

use Battleship\Domain\BoardRepository;
use Battleship\Domain\CellRepository;
use Battleship\Infrastructure\CoordinateType;
use Battleship\Infrastructure\DoctrineBoardRepository;
use Battleship\Infrastructure\DoctrineCellRepository;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\ServiceProvider;

class BattleshipsServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        Type::addType('coordinate', CoordinateType::class);

        $this->app->singleton(BoardRepository::class, DoctrineBoardRepository::class);
        $this->app->singleton(CellRepository::class, DoctrineCellRepository::class);
    }
}
