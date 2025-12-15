<?php

namespace Battleship\Application;

readonly class PlaceShip
{
    public function __construct(
        public string $gameId,
        public string $boardId,
        public string $shipId,
        public int $row,
        public int $column,
        public string $direction,
    ) {
    }
}
