<?php

namespace Battleship\Application;

readonly class StartGame
{
    public function __construct(public string $gameId, public array $boardIds) {}
}
