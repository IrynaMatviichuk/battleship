<?php

namespace Battleship\Application;

use Battleship\Domain\Game;
use Battleship\Domain\GameRepository;
use Battleship\Domain\BoardRepository;

class FireMissileHandler
{
    public function __construct(private GameRepository $games) {}

    public function handle(FireMissile $command): void
    {
        $game = $this->games->findById($command->gameId);

        $game->guess($command->boardId, $command->coordinate);
    }
}
