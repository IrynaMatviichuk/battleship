<?php

namespace Battleship\Domain;

class GameOver
{
    public function __construct(
        private readonly string $lostBoardId,
    ) {
    }
}
