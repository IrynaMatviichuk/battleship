<?php

namespace Battleship\Domain;

class GuessWasMade
{
    public function __construct(
        private readonly string $cellId,
        private readonly Coordinate $coordinate,
        private readonly bool $isAHit,
        private readonly ?string $shipId,
    ) {}

    public function isAHit(): bool
    {
        return $this->isAHit;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
