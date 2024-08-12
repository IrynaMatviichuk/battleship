<?php

namespace Battleship\Domain;

class Ship
{
    private int $size;

    /** @var Cell[] cells */
    private array $cells;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

    public function place(array $cells): void
    {
        if (count($cells) !== $this->size) {
            throw new \InvalidArgumentException();
        }

        $occupiedCells = array_map(function (Cell $cell) {
            return $cell->occupy();
        }, $cells);

        $this->cells = $occupiedCells;
    }

    public function getCells(): array
    {
        return $this->cells;
    }
}
