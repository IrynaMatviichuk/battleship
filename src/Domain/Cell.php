<?php

namespace Battleship\Domain;

class Cell
{
    public readonly int $id;
    private ?int $shipId = null;
    private Coordinate $coordinate;
    private ?bool $guessed = null;

    public function __construct(int $id, Coordinate $coordinate)
    {
        $this->id = $id;
        $this->coordinate = $coordinate;
    }

    public function guess(): Cell
    {
        $cell = new Cell($this->id, $this->coordinate);
        $cell->shipId = $this->shipId;
        $cell->guessed = $this->shipId !== null;

        return $cell;
    }

    public function occupy(int $shipId): Cell
    {
        if ($this->shipId !== null) {
            throw new \InvalidArgumentException();
        }

        $cell = new Cell($this->id, $this->coordinate);
        $cell->shipId = $shipId;

        return $cell;
    }

    public function isGuessed(): ?bool
    {
        return $this->guessed;
    }

    public function isOccupied(): bool
    {
        return $this->shipId !== null;
    }

    public function getRow(): int
    {
        return $this->coordinate->getRow();
    }

    public function getColumn(): int
    {
        return $this->coordinate->getColumn();
    }
}
