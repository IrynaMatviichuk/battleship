<?php

namespace Battleship\Application;

use Battleship\Domain\Game;
use Battleship\Domain\GameRepository;

class StartGameHandler
{
    public function __construct(
        private GameRepository $games,
    ) {
    }

    public function handle(StartGame $command): void
    {
        $game = Game::startGame($command->gameId, ...$command->boardIds);

        $this->games->add($game);
    }
}
