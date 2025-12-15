<?php

namespace Battleship\Domain;

interface GameRepository
{
    public function findById(string $gameId): Game;

    public function add(Game $game): void;
}
