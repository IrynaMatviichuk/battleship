<?php

namespace Battleship\Domain;

class Cell
{
    private Coordinate $coordinate;
    private bool $occupied = false;
    private ?bool $guessed = null;

    public function __construct(Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;
    }

    public function guess(): Cell
    {
        $cell = new Cell($this->coordinate);
        $cell->occupied = $this->occupied;
        $cell->guessed = $this->occupied;

        return $cell;
    }

    public function occupy(): Cell
    {
        if ($this->occupied) {
            throw new \InvalidArgumentException();
        }

        $cell = new Cell($this->coordinate);
        $cell->occupied = true;

        return $cell;
    }

    public function isGuessed(): ?bool
    {
        return $this->guessed;
    }

    public function isOccupied(): bool
    {
        return $this->occupied;
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
