<?php

namespace Battleship\Domain;

class ShipHasSunk
{
    public function __construct (
        public readonly string $shipId,
    ) {
    }
}
