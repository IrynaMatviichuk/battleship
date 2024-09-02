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

    public static function fromCell(Cell $cell): self
    {
        return new GuessWasMade(
            $cell->id,
            $cell->getCoordinate(),
            $cell->isGuessed(),
            $cell->getShipId(),
        );
    }

    public function isAHit(): bool
    {
        return $this->isAHit;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getShipId(): ?int
    {
        return $this->shipId;
    }
}
