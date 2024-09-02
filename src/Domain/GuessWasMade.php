<?php

namespace Battleship\Domain;

class GuessWasMade
{
    public function __construct(
        private readonly int $cellId,
        private readonly Coordinate $coordinate,
        private readonly bool $isAHit,
        private readonly ?int $shipId,
    ) {}

    public function isAHit(): bool
    {
        return $this->isAHit;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

//    public function getShipId(): ?int
//    {
//        return $this->shipId;
//    }
}
