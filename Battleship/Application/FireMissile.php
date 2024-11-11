<?php

namespace Battleship\Application;

use Battleship\Domain\Coordinate;

readonly class FireMissile
{
    public function __construct(public Coordinate $coordinate, public int $boardId) {}
}
