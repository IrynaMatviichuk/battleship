<?php

namespace Battleship\Domain;

class GuessWasMade
{
    public function __construct(
        private readonly Cell $cell,
        private  readonly Coordinate $coordinate,
    ) {}

    public function isAHit(): bool
    {
        return $this->cell->isOccupied();
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
