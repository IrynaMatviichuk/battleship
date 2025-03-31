<?php

namespace Battleship\Application;

readonly class MakeGuess
{
    public function __construct(
        public string $boardId,
        public int $row,
        public int $column,
    ) {
    }
}
