<?php

namespace Battleship\Domain;

use http\Exception\InvalidArgumentException;

class Cell
{
    private bool $occupied = false;
    private ?bool $guessed = null;

    public function guess(): Cell
    {
        $cell = new Cell();
        $cell->occupied = $this->occupied;
        $cell->guessed = $this->occupied;

        return $cell;
    }

    public function occupy(): Cell
    {
        if ($this->occupied) {
            throw new \InvalidArgumentException();
        }

        $cell = new Cell();
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
}
