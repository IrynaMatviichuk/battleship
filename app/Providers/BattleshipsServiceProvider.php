<?php

namespace App\Providers;

use Battleship\Domain\BoardRepository;
use Battleship\Domain\GameRepository;
use Battleship\Domain\ShipRepository;
use Battleship\Infrastructure\CoordinateType;
use Battleship\Infrastructure\DoctrineBoardRepository;
use Battleship\Infrastructure\DoctrineGameRepository;
use Battleship\Infrastructure\DoctrineShipRepository;
use Battleship\Infrastructure\PhaseType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\ServiceProvider;

class BattleshipsServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        Type::addType('coordinate', CoordinateType::class);
        Type::addType('phase', PhaseType::class);

        $this->app->singleton(BoardRepository::class, DoctrineBoardRepository::class);
        $this->app->singleton(ShipRepository::class, DoctrineShipRepository::class);
        $this->app->singleton(GameRepository::class, DoctrineGameRepository::class);
    }
}
