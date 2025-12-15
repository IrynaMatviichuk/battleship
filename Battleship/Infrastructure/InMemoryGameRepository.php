<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Game;
use Battleship\Domain\GameRepository;

class InMemoryGameRepository implements GameRepository
{
    private array $games;

    public function __construct(array $games = []) {
        foreach ($games as $game) {
            $this->games[$game->id] = $game;
        }
    }

    public function findById(string $gameId): Game
    {
        return $this->games[$gameId];
    }

    public function add(Game $game): void
    {
        $this->games[$game->id] = $game;
    }
}
